Jager Template Engine
==============
<h3>A samantic html template engine.</h3>

<p> I started building JTE becuase I want a better more legible template engine. This engine is still very early, not even near a alpha.</p>
<p>Example ::</p>

```
// your markup
<footer data-jager="true" data-include="footercontent.html"></div>

// your parsed markup
<footer>
 <div>@copy 2012 mysite.com</div>
 <ul>
 	<li><a href="#">some linke</a></li>
 	<li><a href="#">some linke</a></li>
 	<li><a href="#">some linke</a></li>
 </ul>
</footer>

```