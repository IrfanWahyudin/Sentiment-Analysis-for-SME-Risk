<html>
<!-- bootstrap 3.0.2 -->
	<head>
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../assets/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="../assets/css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="../assets/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="../assets/css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="../assets/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../assets/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    </head>    
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Risk Search
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
	            <div class="navbar-right">
	                <ul class="nav navbar-nav">	                	
						<form action="http://localhost/riskcluster2/index.php/RiskResult" method="get" class="sidebar-form">
						    <div class="input-group">
						        <input type="text" name="query" class="form-control" placeholder="Search..."/>

						        <span class="input-group-btn">						        	
						            <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
						        </span>
						    </div>
						</form>

					</ul>
				</div>
			</nav>
		</header>
		<?php
			$query = $_GET["query"];
			$query_splitted = explode(" ", $query);
			$batfile = "C:\\xampp\\htdocs\\riskcluster2\\lsi.bat";
			$myfile = fopen($batfile, "w") or die("Unable to open file!");

			$dir = 'cd "C:\Users\irfan\Documents\Visual Studio 2013\Projects\PythonApplication1\RiskCluster\PythonApplication1\"';
			$program = 'python "C:\Users\irfan\Documents\Visual Studio 2013\Projects\PythonApplication1\RiskCluster\PythonApplication1\i_lsi.py "';
			fwrite($myfile, $dir . "\n");

			$command_str = $program;
			foreach ($query_splitted as $value){
				$command_str .=  " ". $value;
			}

			fwrite($myfile, $command_str);
			fclose($myfile);

			$command1 = escapeshellcmd($batfile);
			$output = shell_exec($command1);
			echo $output;
			$output_splitted = explode("|", $output);
			$i = 1;

			$db = new SQLite3('C:\demo\DB_WORDS');
			// $sql = 'select id, substr(content,1,500) as content, cluster, level  from mst_corpus';			
			$sql = 'select * from (';

			foreach ($output_splitted as $value){
				if ($value != ""){
					if ($i>=2 & $i<=100){
						$doc_ids = explode(">", $value);
						// print_r( $doc_ids);
						if (count($doc_ids > 1)){
							if ($i<=100){
								$sql .= 'select ' . $i . ' as no, id, substr(content,1,500) as content, cluster, level from mst_corpus where id = ' . $doc_ids[1];
								if ($i<100)
										$sql .= " UNION ";	
							}
							
						}
					}
					$i++;
						
				}		
			}
			echo $sql;
			$sql .= ') order by no';
			$results = $db->query($sql);
			echo '<section class="content">';
            echo '        <div class="row">';
            echo '            <div class="col-xs-12">';
            echo '                <div class="box">';
            echo '                   <div class="box-header">';
            echo '                        <h3 class="box-title">Search Result</h3>';
            echo '                    </div>';//<!-- /.box-header -->
            echo '                    		<div class="box-body table-responsive">';
            echo '                        		<table id="example2" class="table table-bordered table-hover">';
            echo '								<thead>';
		    echo '                                        <tr>';
		    echo '                                            <th>No</th>';
		    echo '                                            <th>Id</th>';
		    echo '                                            <th>Content</th>';
		    echo '                                            <th>Cluster #</th>';
		    echo '                                            <th>Level</th>';
		    echo '                                        </tr>';
		    echo '                              </thead>';
		    $i = 1;
		    echo '								<tbody>';
		    
			while ($row = $results->fetchArray()) {
				echo '										<tr>';
				echo '										<td>' . $i . '</td>';	
				echo '										<td>' . $row['id'] . '</td>';	
			    echo '										<td>
			    <a href="http://localhost/riskcluster2/index.php/DisplayDoc?doc_id=' . $row['id'] . '">' . $row['content'] . "..." . '</a></td>';
			    echo '										<td>' . $row['cluster'] . '</td>';	
			    echo '										<td>' . 
			    $status = "";
			    if ($row['level']>=1 & $row['level']<=2)
			    	$status = 'success';
			    elseif ($row['level']>=3 & $row['level']<=4)
			    	$status = 'yellow';
			    elseif ($row['level']>=5 & $row['level']<=6)
			    	$status = 'danger';
			    echo    									'<div class="progress xs">'.
                                                    				'<div class="progress-bar progress-bar-' . $status .  '" style="width: ' . $row['level']*20 . '%"></div>';                                                					
			    echo '										</tr>';	
			    $i++;
			}
			echo ' 								</table>';
			echo ' 							</div>';
			echo ' 					</div>';
			echo '				</div>';
			echo '		</div>';
			echo '<section>';
		?>
	</body>

        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="../assets/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="../assets/js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="../assets/js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="../assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="../assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="../assets/js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="../assets/js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="../assets/js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="../assets/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="../assets/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="../assets/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../assets/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

        <!-- AdminLTE App -->
        <script src="assets/js/AdminLTE/app.js" type="text/javascript"></script>
        
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="assets/js/AdminLTE/dashboard.js" type="text/javascript"></script>
        <!-- page script -->
        <script>
        function openWindow(doc_id){
        	var url = 'http://localhost/riskcluster2/index.php/DisplayDoc?doc_id=' + doc_id; 
			window.open(url,"_blank","toolbar=yes, scrollbar=yes,
					resizable=yes, top=500, left=500, width=400, height=400");        	
        }
        </script>
        <script type="text/javascript">
            $(function() {
                $("#example1").dataTable();
                $('#example2').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
            });
        </script>

</html>
