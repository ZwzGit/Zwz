<nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container-fluid">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">hhh</span>
            <span class="sr-only">hhh</span>
            <span class="sr-only">hhh</span>
            <span class="sr-only">hhh</span>
        </button>
        <a class="navbar-brand" href="#">ZWZ</a>
    </div>
    {{ elements.getMenu() }}
</nav>

<div class="container">
    {{ flash.output() }}
    {{ content() }}
    <hr>
    <footer>
        <p>&copy; Company 2015</p>
    </footer>
</div>