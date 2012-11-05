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
