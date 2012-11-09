Jager Template Engine
==============
<h3>A Semantic  HTML template engine.</h3>

<p> I started building JTE becuase I want a better more legible template engine. This engine is still very early, not even near a alpha.</p>
<p>Examples::</p>



## Include Example

Simple example showing how to include a file
```html
<footer  data-jager-include="footercontent.html"></footer>
```
Footer template (footercontent.html)
```html
       <p>&copy; Company 2012</p>
        <ul>
		    <li><a href="#">some link</a></li>
		    <li><a href="#">some link</a></li>
		    <li><a href="#">some link</a></li>
		 </ul>
```

This is the output you would get
```html
<footer>
 <div>&copy; 2012 mysite.com</div>
 <ul>
 	<li><a href="#">some link</a></li>
 	<li><a href="#">some link</a></li>
 	<li><a href="#">some link</a></li>
 </ul>
</footer>
```

##Simple Variable Example 
template example
```html
<title data-jager-var="pagetitle">Place Holder Title</title>
```
template output
```html
<title>Homepage</title>
```


## Foreach
Looping over an array
```php
$jager->data['entries'] = array(
			array(
				'title'=>'Entry 1',
				'description' => "This is a fake description.1",
				'test'=> "this is a test "
				),
			array(
				'title'=>'Entry 2',
				'description' => "This is a fake description.2"
				),
			array(
				'title'=>'Entry 3',
				'description' => "This is a fake description.3"
				)
	);
```
inside your template
```html
      <div class="row" data-jte-foreach="entries">
        <div class="span4">
          <h2>{{title}}</h2>  // notice  it will leave the tag {{*}} inplace if there is no value
          <p>{{description}}</p>
          <p>{{test}}</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
      </div>
```