<?php

require_once(__DIR__ . '/../bootstrap.php');

$vs = new \Saritasa\Tools\VS();

// Execution Time.
// *****************************************************************************

// Strings
$vs->group('Strings');

require_once(__DIR__ . '/time_quotes.php');

// Loops
$vs->group('Loops');

require_once(__DIR__ . '/time_loops.php');

// Numbers & Operations
$vs->group('Numbers & Operations');

require_once(__DIR__ . '/time_increment.php');
require_once(__DIR__ . '/time_decrement.php');

// Comparisons
$vs->group('Comparisons');

require_once(__DIR__ . '/time_equality.php');

// Arrays
$vs->group('Arrays');

require_once(__DIR__ . '/time_arrays_vs_fixed_arrays.php');

// Execution Time.
// *****************************************************************************

// Arrays & classes
$vs->group('Arrays & classes');

require_once(__DIR__ . '/space_assoc_arrays_vs_classes.php');
require_once(__DIR__ . '/space_arrays_vs_fixed_arrays.php');

$vs->display();