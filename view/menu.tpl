<!-- Navbar
================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <a class="brand" href="/">Sohrani.info</a>
        <div class="nav-collapse collapse">
            {if $Auth}
            <div id="linkForm">
                <form class="form form-inline" method="POST" action="" onsubmit="LinkForm.submit(); return false;" name="new">
                    <input type="hidden" value="new" id="form_name" name="form_name">
                    <input type="hidden" value="" id="form_back_url" name="form_back_url">
                    <input type="hidden" value="" id="form_action" name="form_action">
                    <input type="text" id="link" value="" name="link" maxlength="255" placeholder="Link" autofocus="autofocus">
                    <button class="btn" type="submit">Save</button>
                </form>
            </div>
            {/if}
        <div class="pull-right">
            {if $Auth}
                <a class="btn btn-inverse" href="logout">
                    <i class="icon-off icon-white"></i>
                    Logout
                </a>
            {else}
                <a class="btn btn-inverse" href="login">
                    <i class="icon-user icon-white"></i>
                    Login
                </a>
            {/if}
        </div>
        </div>

    </div>
</div>