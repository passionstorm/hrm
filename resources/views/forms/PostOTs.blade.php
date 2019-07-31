<div id="displayAlert1"></div>
<form id="dynamic_form">
    <div class="ccc"></div>
    <div class="text-center">
        <button type="button" class="btn btn-primary s-text" id="add" style="width: 85px; margin-right:5px{{$display}}">Add more</button>
        <button type="button" class="btn s-text" id="reset" style="width: 85px; margin-right:5px; display: none">Reset</button>
        <div class="btn-group dropup save-btn" style="display: none">
            <input type="submit" name="save" id="save" class="btn btn-success s-text" value="Save" style="width: 75px" />
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu o-shadow" role="menu">
                <li><a href="#" style="color: black" id="s-d">Save as Draft</a></li>
            </ul>
        </div>
    </div>
    <br>
</form>
