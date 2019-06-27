<?php
$countries = Constants::COUNTRIES;
if (isset($project)) {
    $projectId = $project->id;
    $actionSubmit = 'projects/edit/' . $project->id;;
    $form = $project;
} else {
    $actionSubmit = 'projects/edit';
    $projectId = '';
    $form = (object)array(
        'c_country' => '',
        'name' => '',
        'c_name' => '',
        'budget' => '',
        'deadline' => '',
        'describe' => '',
    );
}
?>

<form action="{{$actionSubmit}}" method="post">
    @csrf
    <div class="box-body" style="padding-bottom: 20px">
        <div class="form-group">
            <label>Country of customers</label>
            <select class="form-control" name="c_country">
                @foreach( $countries as $country )
                    <option value="{{ array_search( $country, $countries ) }}"
                        @if($form->c_country == $country)
                            {{'selected'}}
                        @endif
                        >{{$country}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Name of project</label>
            <input type="text" class="form-control" name="name" value="{{$form->name}}">
        </div>
        <div class="form-group">
            <label>Customer name</label>
            <input type="text" class="form-control" name="c_name" value="{{$form->c_name}}">
        </div>
        <div class="form-group">
            <label>Budget</label>
            <input type="number" class="form-control" name="budget" min="0" value="{{$form->budget}}">
        </div>
        <!-- Date -->
        <div class="form-group">
            <label>Deadline:</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="datepicker" name="deadline"
                       value="{{$form->deadline}}">
            </div>
        </div>

        <div class="form-group">
            <label>Describe</label>
            <textarea name="describe" id="editor1" rows="10" cols="80">{{$form->describe}}</textarea>
        </div>

        <div class="pull-right">
            @if($projectId)
            <a href="projects/delete/{{$projectId}}" class="btn btn-danger">Close project</a>
            @endif
            <button type="submit" class="btn btn-primary" style="width: 10em">Submit project</button>
        </div>
    </div>
</form>