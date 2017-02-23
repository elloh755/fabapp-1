<?php
/*
 *   CC BY-NC-AS UTA FabLab 2016-2017
 *   FabApp V 0.9
 */
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/header.php');
?>
<title><?php echo $sv['site_name'];?> Admin Base</title>
<?php if ($staff) if($staff->getRoleID() < 7){
    //Not Authorized to see this Page
    header('Location: /index.php');
}?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Service Replies</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calculator fa-fw"></i> Tickets
                </div>
                <div class="panel-body" style="max-height: 10;overflow-y: scroll;">
                    <?php 
                    if ($result = $mysqli->query("
                    SELECT sc_id, staff_id, d_id, sl_id, sc_time, solved, notes
                    FROM service_call
                    WHERE solved = 'N'
                    ORDER BY sc_time DESC
            ")){
                                while ( $row = $result->fetch_assoc() ){ ?>
                                        <tr class="tablerow">
                                                <?php if($row["t_start"]) { ?>
                                                        <td align="right"><?php echo $row["sc_id"]; ?></td>
                                                        <?php if($row['url'] && (preg_match($sv['ip_range_1'],getenv('REMOTE_ADDR')) || preg_match($sv['ip_range_2'],getenv('REMOTE_ADDR'))) ){ ?>
                                                                <td><?php echo ("<a href=\"http://".$row["url"]."\">".$row["device_desc"]."</a>"); ?></td>
                                                        <?php }else{ ?>
                                                                <td><?php echo $row["device_desc"]; ?></td><?php }
                                                        echo("<td>".date( 'M d g:i a',strtotime($row["t_start"]) )."</td>" );
                                                        if( isset($row["est_time"]) ){
                                                                echo("<td align=\"center\"><div id=\"est".$row["trans_id"]."\">".$row["est_time"]." </div></td>" ); 
                                                        } else 
                                                                echo("<td align=\"center\">-</td>"); 
                                                        if ($staff) {?>
                                                        <td align="center">
                                                                <button onclick="endTicket(<?php echo $row["trans_id"].",'".$row["device_desc"]."'"; ?>)">End Ticket</button>
                                                        </td>
                                                        <?php } if ( isset($row["est_time"])) {
                                                                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $row["est_time"]);
                                                                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                                                                $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
                    
                                                                $time_seconds = $time_seconds - (time() - strtotime($row["t_start"]) ) + $sv["grace_period"];
                                                                array_push($device_array, array($row["trans_id"], $time_seconds, $row["dg_parent"]));
                                                        } 
                                                } else { ?>
                                                        <td align="right"></td>
                                                        <?php if($row['url'] && (preg_match($sv['ip_range_1'],getenv('REMOTE_ADDR')) || preg_match($sv['ip_range_2'],getenv('REMOTE_ADDR'))) ){ ?>
                                                                <td><?php echo ("<a href=\"http://".$row["url"]."\">".$row["device_desc"]."</a>"); ?></td>
                                                        <?php }else{ ?>
                                                                <td><?php echo $row["device_desc"]; ?></td><?php }?>
                                                        <td align="center"> - </td>
                                                        <td align="center"> - </td>
                                                        <?php if($row["url"] && $staff){ ?>
                                                                <td  align="center"><?php echo ("<a href=\"http://".$row["url"]."\">New Ticket</a>"); ?></td>
                                                        <?php }elseif($staff) { ?>
                                                                <td align="center"><div id="est"><a href="\pages\create.php?<?php echo("d_id=".$row["d_id"])?>">New Ticket</a></div></td>
                                                        <?php }?>
                    
                                                <?php } ?>
                                        </tr>
                                                <?php
                                        }
                    
                                } 
                        ?>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <div class="row">
        <!-- /.col-lg-8 -->
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-folder fa-fw"></i> Replies
                </div>
                <div class="panel-body">
                    
                </div>
            </div>
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php
//Standard call for dependencies
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/footer.php');
?>