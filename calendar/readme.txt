=======================
calendar.class.php v2.6
=======================


1. About calendar.class.php
2. Requirements
3. What's new in this version
4. Installation and use
5. Help & feature requests
6. Copyright



1. About calendar.class.php
===========================

calendar.class.php is an easy to use php class for generating html calendars. The html code 
output by this class is clean, semantic, valid and easily styled with CSS.



2. Requirements
===============

- PHP 4 or later



3. What's new in this version
=============================

- Bug Fix: starting week on certian days causes a calendar display issues
	   Property $week_start_on has been replaced by $week_start

For complete feature list visit style-vs-substance.com



4. Installation and use
=======================

a. Upload the contents of this zip file to your website.

b. include calendar.class.php into your page
   require_once('calendar.class.php');

c. create an instance of the calendar object
   $calendar = new Calendar();

d. call the class's output method
   print($calendar->output_calendar());

For more examples and advanced use view the pages in the examples directory included 
with this release or visit style-vs-substance.com



5. Help & feature requests
==========================

Visit style-vs-substance.com to request help or suggest additional features



6. Copyright
============

calendar.class.php v2.6
copyright © 2008 Jim Mayes
licensed under: Creative Commons Attribution-Share Alike 3.0 License 
http://creativecommons.org/licenses/by-sa/3.0/
This class may not be used for commercial purposes without written consent.



