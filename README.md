# ZLE: An extension to Zend Framework library

## What is it?
This is a collection of classes to be used in conjunction with Zend Framework
to provide functionalities that are not part of the framework. I use these classes
as extension of the original ZF.

## Usage
* Put the library/Zle folder in your php include path, then simply load the namespace using 
the `application.ini` file in your zf app by including this line
    autoloadernamespaces[] = "Zle_"
* In order to setup paths for other library components you may also want to include the following lines
    pluginPaths.Zle_Application_Resource = "Zle/Application/Resource" # application resources
    resources.frontController.actionhelperpaths.Zle_Controller_Action_Helper_ = "Zle/Controller/Action/Helper" # action helpers
    resources.view.helperPath.Zle_View_Helper = "Zle/View/Helper" # view helpers

## Language Support
* PHP >= 5.2 (in the near future this requirement will be upgraded to 5.3)

## Limitations
* The code relies on the autoloading functionality of ZF (or any other PEAR compliant autoloader),
so in order to use this library you must setup autoloading (there are no requires in the code)
* At this time the code lacks of documentation (however it is pretty self-explanatory)
* Currently code is tested against Zend Framework 1.11.x. In the future the code will be rewritten
 to work with ZF2 when it reaches a stable branch.

## Build process

Note: you can skip this process if you just want to use the library code, only read if you are
interested in contributing of if you want to generate the api documentation.

In order to keep the code tested and clean I've added an ant script to the code in the file
`build.xml`. This script can be run using the `ant` command and it will trigger some tasks:
* Run the unittests for the library and generates code coverage report
* Run phpmd [PHP Mess Detector](http://phpmd.org/)
* Run pdepend [PHP Depend](http://pdepend.org/)
* Run phpcs [PHP CodeSniffer](http://pear.php.net/package/PHP_CodeSniffer/redirected) source code analyzer
* Run the phpdoc [phpDocumentor](http://www.phpdoc.org/) in order to generate api doc

After the build process you can find the generated artifacts in the `build` directory.

If you only want the api documentation you can run the command `ant phpdoc` at the top level directory.

## Contributing

Why not? If you're interested fork this project on github and send me a pull request with your patches.
Please ensure that you write unittests and respect the PEAR code convention when coding, you can run the
`phpcs` script to ensure the code is compliant with that standard.
