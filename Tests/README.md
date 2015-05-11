# Running tests
In order to run the tests, you have configure your environment:
 1. Create a Joomla! installation
 2. Install required libraries with Composer

### Create a Joomla! installation
We need a running installation of Joomla! in order to load all the required libraries.
Once you installed the latest version of Joomla!, you have to copy the file `Tests/config.dist.php`, rename it to `Tests/config.php` and update the contents its contents, providing the full path to your local installation:
```php
$fofTestConfig = array(
	// Point to a path where a Joomla! 2.5 / 3.x site is stored. It's our guinea pig!
	'site_root' => '/Applications/MAMP/htdocs/unittesting3'
);
```
### Install required libraries with Composer
You simply have to run `php composer.phar update` in order to install and update all the required libraries.

### How to run tests
Inside the `Tests` folder you can find a shell file named `run-tests.sh`.
If you execute it, by default you'll run the whole test suite in random order: in this way we can double check if there is any entagled test polluting the whole suite.
`./run-tests.sh`
```
Time: 3.63 seconds, Memory: 22.75Mb

OK, but incomplete, skipped, or risky tests!
Tests: 951, Assertions: 1954, Incomplete: 3.

Randomized with seed: 6625
```

You can force the execution of the tests with a specific seed, by passing it as argument: `./run-tests.sh 6625`. This is very useful when you find that the suite is failing if tests are ran in a specific order
```
Time: 3.63 seconds, Memory: 22.75Mb

OK, but incomplete, skipped, or risky tests!
Tests: 951, Assertions: 1954, Incomplete: 3.

Randomized with seed: 6625
```

Finally, you can revert to the regular order using the `native` argument. In this case there is no randomization and the tests are ran in the same order as they are coded:
`./run-tests.sh native`
```
Time: 3.63 seconds, Memory: 22.75Mb

OK, but incomplete, skipped, or risky tests!
Tests: 951, Assertions: 1954, Incomplete: 3.
```