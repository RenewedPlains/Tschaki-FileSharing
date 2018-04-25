<?php
session_start();
$userid = $_SESSION['userid'];
include 'connection.php';
$id = $_GET['id'];
$value = $_GET['val'];
$valar = explode(',', $value);
// print_r($valar);
$select_all_folder = "SELECT * FROM `files` WHERE `user` = '$userid' AND `mimetype` = 'folder' ORDER BY `folderpath` ASC";
$select_all_folder_query = mysqli_query($db, $select_all_folder);
echo '<div data-path="files/'.$userid.'" class="moveselecter">/</div>';
if(mysqli_num_rows($select_all_folder_query) == 0) {
	echo 'No Folders found.';
} else {
	while($folderout = mysqli_fetch_assoc($select_all_folder_query)) {
		$pathold = addslashes($folderout['folderpath'] . "/" . $folderout['name']);
		$safepath = addslashes("files/$userid");
		$folderepath = ltrim($pathold, $safepath);
		echo "<div data-path='".$folderout['folderpath']."/".$folderout['name']."' class='moveselecter'>/".$folderepath . "</div>";
	}
}
?>

<script>
    function loadfiles() {
        var path = $('#grid').attr('data-path');
        $.ajax({
            url: "/datagrid.php?path=" + path
        })
            .done(function(html) {
                $("#grid").html(html);
                $('#selectinfo').animate({'marginBottom': '-60px'}, 200);
            });
    }

    $(function() {
        $('.moveselecter').click(function() {
            var newpath = $(this).attr('data-path');
            $.ajax({

                url: "/scripts/movedata.php",
                method: "post",
                data: { value : '<?php echo $value; ?>', newpath : newpath }
            })
                .done(function(html) {
                    loadfiles();
                    $.sweetModal({
                        content: html,
                        icon: $.sweetModal.ICON_SUCCESS,
                        onClose: function(sweetModal) {
                            $('.dark-modal').remove();
                        }
                    });
                })

        });
    });
</script>