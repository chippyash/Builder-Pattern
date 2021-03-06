# Chippyash Builder Pattern

## 
      Chippyash\Test\BuilderPattern\AbstractBuilder
    

*  Get data object returns array
*  Setting simple value will store in builder parameters
*  Setting a closure for a parameter will store result of closure
*  Setting a builder for a parameter will build nested array
*  Builder will use set method if one is available
*  Builder will proxy to set if set method not found
*  Set will throw exception for unknown parameter
*  Builder will use get method if one is available
*  Builder will proxy to get if get method not found
*  Get will throw exception for unknown parameter
*  Isset will return true for parameter that has value
*  Isset will return false for parameter that has no value
*  Isset will return false for unknown parameter
*  Unset will set parameter to null
*  Unset will throw exception for unknown parameter
*  Builder will use discovery method if one is available
*  Builder will proxy to isset if discovery method not found
*  Method proxy will throw exception if method not supported
*  Can set modifier
*  Setting the modifer will trickle down to child builders
*  Can call modify to trigger events
*  Calling modify if no modifier set will return empty response collection
*  Build will return false if build fails

## 
      Chippyash\Test\BuilderPattern\AbstractCollectionBuilder
    

*  Build returns true if collection builds successfully
*  Build returns false if collection build fails
*  We can add a builder
*  We can set and get the collection
*  Setting the collection will clear previous collection
*  Can set modifier
*  Can call modify to trigger events
*  Setting the modifer will trickle down to collection builders

## 
      Chippyash\Test\BuilderPattern\AbstractDirector
    

*  Build will return value if build succeeds
*  Build will throw exception if build fails
*  Setting a modifier will set the modifier
*  Calling modify will return an empty zend event response collection if modifier not set
*  Calling modify will return a zend event response collection if modifier set
*  Can add modifications


Generated by [chippyash/testdox-converter](https://github.com/chippyash/Testdox-Converter)