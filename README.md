# Text Message Voting Application

====================

This is a text message voting application that I originally completed as a project for the [Rapid Prototyping and Creative Development](http://classes.engineering.wustl.edu/cse330/index.php/CSE_330_Online_Textbook_-_Table_of_Contents)
course at Washington University in St. Louis. It is based on the Audience Voting App created by [Philip Thomas](http://philipithomas.com/voting-application/)
for a dance competition which I organized in April, 2013.

The software is currently set up to properly handle all voting and give a simple graphical output for the sake of development,
but databases and a Twilio account must be set up manually, and it can only handle one poll at a time. Future versions will include
an admin interface and abstract the poll creater from needing to manually interact with databases. Further result analysis
will be available as well.

This application uses a MySQL database and the [official PHP Twilio REST API and TwiML library](https://www.twilio.com/docs/libraries).
The MySQL tables are set up as described below:

choices:

+-------+---------------------+------+-----+---------+----------------+
| Field | Type                | Null | Key | Default | Extra          |
+-------+---------------------+------+-----+---------+----------------+
| id    | tinyint(3) unsigned | NO   | PRI | NULL    | auto_increment |
| name  | varchar(200)        | NO   |     | NULL    |                |
+-------+---------------------+------+-----+---------+----------------+

votes:

+--------+-----------------------+------+-----+---------+----------------+
| Field  | Type                  | Null | Key | Default | Extra          |
+--------+-----------------------+------+-----+---------+----------------+
| id     | mediumint(8) unsigned | NO   | PRI | NULL    | auto_increment |
| number | varchar(15)           | NO   |     | NULL    |                |
| vote   | tinyint(3) unsigned   | YES  | MUL | NULL    |                |
+--------+-----------------------+------+-----+---------+----------------+

In addition, several values need to be set from the user's Twilio account and database users. Instructions are commented into the files.