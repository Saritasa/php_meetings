<?php

require_once(__DIR__ . '/../bootstrap.php');

$vs = new \Saritasa\Tools\VS();

// Execution Time.
// *****************************************************************************

// Strings
$vs->group('Strings');

require_once(__DIR__ . '/quotes.php');

// Loops
$vs->group('Loops');

require_once(__DIR__ . '/loops.php');

// Numbers
$vs->group('Numbers');

require_once(__DIR__ . '/increment.php');
require_once(__DIR__ . '/decrement.php');

// Comparisons
$vs->group('Comparisons');

require_once(__DIR__ . '/equality.php');

// Arrays
$vs->group('Arrays');

require_once(__DIR__ . '/time_arrays_vs_fixed_arrays.php');

// Execution Time.
// *****************************************************************************

// Arrays & classes
$vs->group('Arrays & classes');

require_once(__DIR__ . '/assoc_arrays_vs_classes.php');
require_once(__DIR__ . '/space_arrays_vs_fixed_arrays.php');

$vs->display();