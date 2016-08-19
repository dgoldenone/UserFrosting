<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2013-2016 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */
 
namespace UserFrosting\Sprinkle\Core\Util;

/**
 * UserFrosting class mapper.
 *
 * This creates an abstraction layer for overrideable classes.
 * For example, if we want to replace usages of the User class with MyUser, this abstraction layer handles that.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 * @author Roger Ardibee
 */
class ClassMapper
{
    /**
     * Mapping of generic class identifiers to specific class names.
     */
    protected $classMappings = [];
    
    /**
     * Creates an instance for a requested class identifier.
     * 
     * @param string $identifier The identifier for the class, e.g. 'user'
     * @param mixed ...$arg Whatever needs to be passed to the constructor.
     */     
    public function createInstance($identifier)
    {
        $className = $this->getClassMapping($identifier);
        
        $params = array_slice(func_get_args(), 1);

        // We must use reflection in PHP < 5.6.  See http://stackoverflow.com/questions/8734522/dynamically-call-class-with-variable-number-of-parameters-in-the-constructor        
        $reflection = new \ReflectionClass($className);

        return $reflection->newInstanceArgs($params); 
    }
    
    /**
     * Gets the fully qualified class name for a specified class identifier.
     *
     * @param string $identifier
     * @return string
     */
    public function getClassMapping($identifier)
    {
        if (isset($this->classMappings[$identifier])) {
            return $this->classMappings[$identifier];
        } else {
            // Throw exception
            
        }
    }
    
    /**
     * Assigns a fully qualified class name to a specified class identifier.
     *
     * @param string $identifier
     * @param string $className     
     * @return ClassMapper
     */    
    public function setClassMapping($identifier, $className)
    {
        // Check that class exists
        if (class_exists($className)) {
            $this->classMappings[$identifier] = $className;
        } else {
            error_log('Unable to find a valid class of type \'' . $identifier . '\'.' );
            // Throw exception
            
        }

        return $this;
    }
    
    /**
     * Call a static method for a specified class.
     * 
     * @param string $identifier The identifier for the class, e.g. 'user'
     * @param string $methodName The method to be invoked.
     * @param mixed ...$arg Whatever needs to be passed to the method.
     */      
    public function staticMethod($identifier, $methodName)
    {
        $className = $this->getClassMapping($identifier);
    
        $params = array_slice(func_get_args(), 2);

        return call_user_func_array("$className::$methodName", $params);
    }
}
