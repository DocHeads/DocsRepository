<?php
 include ('../Lib/Session.php');
 Session::validateSession();
include ('../templates/header.php');
include ('../Lib/Submissions.php');
include ('../Lib/Departments.php');
include ('../Lib/Courses.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  Submission::uploadFile();
}
?>

<h2>Submit a document to the Computer Apps Repository (*=required field)</h2>
<form action="submissionUpload.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <label for="docName">Document Name</label>
    <input type="hidden" id="volume" value="1" />
    <input type="text" id="deptName"><br /><br />
	<label for="docFile">Document *</label> &nbsp
	<input type="file" name="fileName[]" size="200" required="required">
	<br>

	<label for="comments">Document Description *</label>
	<textarea	id="comments" name="comments" value="" wrap="virtual" 
									rows="5em" cols="80em"
									valign="top"
									align="left"
									required="required">
	</textarea>
	<br>

	<label for="rubricFileName">Grading Rubric (optional)</label>
	<input type="file" name="fileName[]" id="rubricFileName" class="clsFile">

	<br>

	<label for="instructionsToTheStudent">Instructions to the student (optional)</label>
	<input type="file" name="fileName[]" id="instructionsToTheStudent" class="clsFile">

	<br>

	<label for="instructionsToTheInstructor">Instructions to the instructor (optional)</label>
	<input type="file" name="fileName[]" id="instructionsToTheInstructor" class="clsFile">

	<br>

	<label for="willGrade">Will you grade assignments based on this document? &nbsp </label>
	<input type="radio" name="willYouGrade" id="willYouGrade" value="Yes" class="radio-box" checked >
	Yes &nbsp
	<input type="radio" name="willYouGrade" id="willYouGrade" value="No"  class="radio-box">
	No
	<p>
		<label for="department">Department *</label>   
        <?php
        $department = Departments::getDeptList();
            echo '<select name="department">';
            echo '<option selected="selected">Select your department...</option>';
                foreach($department as $key=>$value) {  
                    echo '<option value="'.$key.'">'.$value.'</option>';
                }  
            echo '</select>';

		    echo '<br>';
		    echo '<label for="course">Course *</label>';

        $course = Courses::getCourseList();
            echo '<select name="course">';
            echo '<option selected="selected">Select your course...</option>';
                foreach($course as $key=>$value) {  
                    echo '<option value="'.$key.'">'.$value.'</option>';
                }  
            echo '</select>';   
         ?> 
		<div class="btn-holder">
			<button type="submit">
				Submit
			</button>
		</div>
</form>

<?php
include ('../templates/footer.html');
?>
