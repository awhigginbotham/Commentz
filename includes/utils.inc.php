<?php
    function confirmDeleteModal($message) {
        echo '<!-- Modal -->' . "\n";
        echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' . "\n";
        echo ' <div class="modal-dialog">' . "\n";
        echo '   <div class="modal-content">' . "\n";
        echo '     <div class="modal-header">' . "\n";
        echo '       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' . "\n";
        echo '       <h4 class="modal-title">Delete confirmation:</h4>' . "\n";
        echo '     </div>' . "\n";
        echo '     <div class="modal-body">' . "\n";
        echo '       Really delete this ' . "$message?\n";
        echo '     </div>' . "\n";
        echo '     <div class="modal-footer">' . "\n";
        echo '       <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' . "\n";
        echo '      <button type="submit" class="btn btn-primary" name="delete-submit">Delete</button>' . "\n";
        echo '     </div>' . "\n";
        echo '   </div><!-- /.modal-content -->' . "\n";
        echo ' </div><!-- /.modal-dialog -->' . "\n";
       echo '</div><!-- /.modal -->' . "\n";
    }
?>