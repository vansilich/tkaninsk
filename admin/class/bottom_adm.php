</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.2
    </div>
    <strong>Copyright &copy; 2007-<? echo date('Y');?> <a href="http://vakas.ru" target="_blank">Vakas</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


 
<? /*  
<!-- daterange picker -->
<link rel="stylesheet" href="<? echo $inc_path;?>includes/plugins/daterangepicker/daterangepicker-bs3.css">
*/ ?>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/plugins/select2/select2.min.css">
  
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/css/admin.css">
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/css/colorbox.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/dist/css/skins/_all-skins.min.css">





<!-- Bootstrap 3.3.5 -->
<script src="<? echo $inc_path;?>includes/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll 1.3.0 прокрутка в диве-->
<script src="<? echo $inc_path;?>includes/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick это для мобильных устройств уберает задержку-->
<script src="<? echo $inc_path;?>includes/plugins/fastclick/fastclick.js"></script>


<!-- Select2 -->
<script src="<? echo $inc_path;?>includes/plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<? echo $inc_path;?>includes/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<? echo $inc_path;?>includes/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<? echo $inc_path;?>includes/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- datepicker -->
<script src="<? echo $inc_path;?>includes/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- bootstrap color picker -->
<script src="<? echo $inc_path;?>includes/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<? echo $inc_path;?>includes/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<? echo $inc_path;?>includes/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript" src="<? echo $inc_path;?>class/ckeditor/ckeditor.js"></script>

<!-- AdminLTE App -->
<script src="<? echo $inc_path;?>includes/dist/js/app.js"></script>

<script type="text/javascript" src="<? echo $inc_path;?>includes/js/jquery.colorbox.js"></script>

<script src="<? echo $inc_path;?>includes/js/script.js"></script>

<? /*
<!-- AdminLTE for demo purposes -->
<script src="<? echo $inc_path;?>includes/dist/js/demo.js"></script>
*/ ?>
<!-- alerts -->
        <div class="modal" id="simple_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Сообщение</h4>
              </div>
              <div class="modal-body">
                <p id="simple_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->

        <div class="modal fade modal-primary" id="primary_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Сообщение</h4>
              </div>
              <div class="modal-body">
                <p id="primary_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->

        <div class="modal modal-info" id="info_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Сообщение</h4>
              </div>
              <div class="modal-body">
                <p id="info_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->

        <div class="modal modal-warning" id="warning_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Сообщение</h4>
              </div>
              <div class="modal-body" >
                <p id="warning_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->

        <div class="modal modal-success" id="success_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Success Modal</h4>
              </div>
              <div class="modal-body">
                <p id="success_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->

        <div class="modal modal-danger" id="danger_window">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Danger Modal</h4>
              </div>
              <div class="modal-body">
                <p id="danger_window_text"></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-outline">Save changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.example-modal -->
<!-- end alerts -->


</body>
</html>