# chippyash/builder-pattern

## QA

Certified for PHP 5.5 - 5.6

[![Build Status](https://travis-ci.org/chippyash/Builder-Pattern.svg?branch=master)](https://travis-ci.org/chippyash/Builder-Pattern)
[![Test Coverage](https://codeclimate.com/github/chippyash/Builder-Pattern/badges/coverage.svg)](https://codeclimate.com/github/chippyash/Builder-Pattern/coverage)
[![Code Climate](https://codeclimate.com/github/chippyash/Builder-Pattern/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Builder-Pattern)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
## What?

Provides an implementation of the [Builder Pattern](http://en.wikipedia.org/wiki/Builder_pattern) 
for PHP.

*  Everything has a test case
*  It's PHP 5.5+

The library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

If you have [PlantUML](http://plantuml.sourceforge.net/) installed, you can view the UML diagrams in the docs folder.

See the test contract in the docs folder.

## Why

Solve the problem once!  The requirement was to have a builder pattern implementation 
that can:

* be used to build test data sets or other items
* allow test scripts to modify builder behaviour via the directors

## How

The solution breaks the problem into parts:

* Builders build a data set
* Modifiers utilise events to communicate changes in the builder behaviour
* Renderers are responsible for doing something with the built data
* Directors control the process (as per the builder pattern) and can communicate to
builders via the Modifier

### Coding Basics

Please see the example script, Builders and Director in the examples folder.  They
demonstrate all the principles of using the library.  Try changing the Director
to use a different Renderer.  Add some additional data items to a builder.  Add a
modification into a builder, with a Director method that allows the client script
to control the build.

### Creating Builders

Extend your builder from AbstractBuilder and add a protected function setBuildItems()
to add items to the $this->buildItems associative array.  Items can be simple
value holders or implementations of another builder. e.g.

<pre>
    protected function setBuildItems()
    {
        $this->buildItems = [
            'name' => '',
            'createdate' => new \DateTime(),
            'account' => new AccountBuilder(),
            'exportName => function(){return 'BuilderPattern!';}
        ];
    }
</pre>

Under normal circumstances, you can reference your builder items using

<pre>
    $builder->itemname = $value
    $value = $builder->itemname
    //or
    $builder->SetItemname($value)
    $value = $builder->getItemname()
</pre>

Using the set... method has the advantage that it presents a fluent interface
thus allowing you to chain setters together.

In some circumstances, you may want to add some special processing to setting
and getting.  Simply create a public setItemname($value) method or getItemname()
method.  Similarly you can provide a hasItemname() method to override default isset()
behaviour.

<pre>
    public function setName($value)
    {   
        $this->buildItems['name'] = ucfirst($value);
        return $this;
    }
</pre>

You can create a collection builder by extending AbstractCollectionBuilder.  In
this case you do not need to add a setBuildItems() method as this is already done 
in the abstract parent class.

Note that adding override methods generally does not make sense for a collection
builder.  Collection builders support methods for adding builders to the collection:

<pre>
    $cBuilder->addBuilder($builder);
    $cBuilder->setCollection(array($builder1, $builder2, ...));
    $collection = $cBuilder->getCollection()
</pre>

All builders support building and getting the results:

<pre>
    if ($builder->build()) {
        $result = $builder->getResult();
    } else {
        //...
    }
</pre>

### Creating Directors

Create a Director by extending the AbstractDirector class and providing it with
a constructor that extends the parent

<pre>
class CustomerDirector extends AbstractDirector
{
    public function __construct()
    {
        parent::__construct(new CustomerBuilder(), new JsonRenderer());
    }
}
</pre>

You can use the supplied renderers, or create your own.  The above snippet is the
minimum you need to do.  You can add setup steps in the constructor:

<pre>
    public function __construct(EventManagerAwareInterface $modifier)
    {
        $builder = new CustomerBuilder();
        parent::__construct($builder, new JsonRenderer());
        
        //set test account details
        $builder->setName('Mrs Felicia Bailey');
        $builder->account->id = '023197';
    }
</pre>

You can also add methods that are available to clients of the director:

<pre>
    public function setName($name)
    {
        $this->builder->setName($name);
        return $this;
    }

    public function setAccountId($id)
    {
        $this->builder->account->setId($id);
        return $this;
    }
</pre>

### Modifying the build

The library supports an event driven modification system that gives you great
control over the build process.  By default the Builders do not support this. To
enable it you need to call the setModifier() method on the root builder, which
will ripple it down the builder tree.  A stock modifier is provided in the form
of Chippyash\BuilderPattern\Modifier, but you can create your own by implementing
the Zend\EventManager\EventManagerAwareInterface if you need to.

If you are using the BuilderPattern in some large system you may want to instantiate
the Modifier outside of the Directors and pass it in, so the event train is shared
between all your application components.  The CustomerDirector example does this:

<pre>
class CustomerDirector extends AbstractDirector
{
    public function __construct(EventManagerAwareInterface $modifier)
    {
        $builder = new CustomerBuilder();
        $builder->setModifier($modifier);
    }
}
</pre>

By default, the AbstractDirector knows about and supports two events, 

* ModifiableInterface::PHASE_PRE_BUILD
* ModifiableInterface::PHASE_POST_BUILD

These are triggered just before build commences and just after success of the build 
respectively. NB. You can create triggers and listeners for other events.

#### Adding modifiers

Adding a modifier (trigger) to the pre or post build trigger stack is straightforward, 
simply call the root builder modify() method.  modify() expects two parameters:

* an array of parameters which must at least contain an 'name' item specifying
the required action.
* the name of an event, usually ModifiableInterface:l:PHASE_PRE_BUILD or
ModifiableInterface::PHASE_POST_BUILD, but it can be any string

Here's an example from the CustomerDirector:

<pre>
    public function buyItem($itemId, $amount)
    {
        $this->builder->modify(
                ['name' => 'addPurchase',
                 'id' => $itemId,
                 'date' => new \DateTime],
                ModifiableInterface::PHASE_PRE_BUILD
                );
        $this->builder->modify(
                ['name' => 'updateBalance',
                 'amount' => $amount],
                ModifiableInterface::PHASE_PRE_BUILD
                );
        
        return $this;
    }
</pre>

The modify() method is best used for the two supported events.  If you want 
to trigger another type of event you can call the trigger method on the event 
manager directly

<pre>
    $modifer->getEventManager()->trigger($eventName, $this, $params);
</pre>

This allows a great degree of control, particularly where you are using builders 
as part of a larger system, and that system is able to modify the eventual build
result a long time before the build actually takes place.

Of course for every trigger, you need one or more listeners.  You put these in
your Builder classes.  In Builders that you want to have listeners, extend the 
setModifier() method to set up your listeners:

<pre>
    public function setModifier(EventManagerAwareInterface $modifier)
    {
        parent::setModifier($modifier);
        $this->modifier->getEventManager()->attach(ModifiableInterface::PHASE_PRE_BUILD, [$this,'preBuildListener']);
    }
</pre>

In this example, we are telling the event manager to use the preBuildListener() method
to answer to a pre build trigger.  A typical implementation might be:

<pre>
    public function preBuildListener(Event $e)
    {
        if ($e->getParam('name') == 'updateBalance') {
            $this->balance += $e->getParam('amount');
        }
    }
</pre>

### A note on renderers

Being able to build some sort of data structure is well and good, but the real
power of the BuilderPattern comes from what you can do with it.  Three basic
renderers are provided:

* PassthruRenderer - simply passes back the result of builder->getResult()
* JsonRenderer - returns builder->getResult() as a Json string
* XmlRenderer - returns builder->getResult() as an XML definition

You will almost certainly want to create your own renderers, simply do this by
implementing the RendererInterface.  Some ideas:

* creation of configured objects (although this is usually better handled through
a dependency injection container; Symfony provides a good one. However, the 
BuilderPattern allows for in-app object construction, rather than at application
start.)
* creating diagrams via an SVG implementation
* firing commands to external processes (I've used this to create entries on a
mock service provider to set up system tests, for instance.) 

### Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

## Where?

The library is hosted at [Github](https://github.com/chippyash/Builder-Pattern).
It is available at [Packagist.org](https://packagist.org/packages/chippyash/builderpattern) as a
[Composable](https://getcomposer.org/) module

### Installation

Install [Composer] (https://getcomposer.org/)

#### For production

add

<pre>
    "chippyash/builderpattern": "~2"
</pre>

to your composer.json "requires" section.

#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Builder-Pattern.git DataBuilder
    cd DataBuilder
    composer install --dev
</pre>

To run the tests:

<pre>
    cd DataBuilder
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## Some other stuff

Check out the other packages at [my blog site](http://the-matrix.github.io/packages/) for more PHP stuff;

## License

This software library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

This software library is Copyright (c) 2015-2016, Ashley Kitson, UK

A commercial license is available for this software library, please contact the author. 
It is normally free to deserving causes, but gets you around the limitation of the GPL
license, which does not allow unrestricted inclusion of this code in commercial works.

## History

1.0.0 - initial version

1.0.1 - integrate travis and coveralls

1.0.2 - complete test pack

1.0.3 - add test contract

1.1.0 - new feature: allow use of closures as build items

1.1.1 - make library agnostic of Zend-EventManager version

2.0.0 - BC Break: change namespace from chippyash\BuilderPattern to Chippyash\BuilderPattern

2.0.1 - move from coveralls to codeclimate