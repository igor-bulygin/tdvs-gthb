### Example DB

The migration scripts will populate the database with the following content:

#### Categories
~~~
Fashion (10000)
	Women (20000)
		Dresses (30000)
	Man (40000)
		Jeans (50000)
Technology (60000)
	Computers (70000)
		RAM (80000)
	Smart phones (90000)
~~~

#### Tags
~~~
//Dropdown
Color (10000) //applied to categories 30000, 50000
	Red (red)
	Green (green)
	Blue (blue)

//Freetext
Weight (20000) //applied to categories 90000
	Weight //numeric

//Freetext
Size (30000) //applied to categories 90000
	Long //numeric
	Wide //numeric
	Tall //numeric
~~~