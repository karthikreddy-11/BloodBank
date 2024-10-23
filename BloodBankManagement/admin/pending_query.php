<?php
session_start();
include 'conn.php'; // Include database connection
include 'session.php'; // Include session management

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        #sidebar { position: relative; margin-top: -20px; }
        #content { position: relative; margin-left: 210px; }
        @media screen and (max-width: 600px) {
            #content { position: relative; margin-left: auto; margin-right: auto; }
        }
        #he {
            font-size: 14px; font-weight: 600; text-transform: uppercase;
            padding: 3px 7px; color: #fff; text-decoration: none;
            border-radius: 3px; text-align: center;
        }
    </style>
</head>
<body style="color:black">
    <div id="header">
        <?php include 'header.php'; ?>
    </div>
    <div id="sidebar">
        <?php $active = "pending_queries"; include 'sidebar.php'; ?>
    </div>
    <div id="content">
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 lg-12 sm-12">
                        <h1 class="page-title">Pending Queries</h1>
                    </div>
                </div>
                <hr>

                <script>
                    function updateStatus(queryId) {
                        if (confirm("Do you really want to mark this as Read?")) {
                            window.location.href = 'pending_queries.php?id=' + queryId;
                        }
                    }
                </script>

                <?php
                // Update query status if an ID is passed
                if (isset($_GET['id'])) {
                    $queryId = $_GET['id'];
                    $sql1 = "UPDATE contact_query SET query_status='1' WHERE query_id={$queryId}";
                    mysqli_query($conn, $sql1);
                    echo '<div class="alert alert-info alert-dismissible"><b><button type="button" class="close" data-dismiss="alert">&times;</button></b><b>Pending Request marked as "Read".</b></div>';
                }

                $limit = 10;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;
                $count = $offset + 1;

                // Fetch pending queries
                $sql = "SELECT * FROM contact_query WHERE query_status = 0 LIMIT {$offset}, {$limit}";
                $result = mysqli_query($conn, $sql);
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered" style="text-align:center">
                        <thead style="text-align:center">
                            <tr>
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Email Id</th>
                                <th>Mobile Number</th>
                                <th>Message</th>
                                <th>Posting Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $row['query_name']; ?></td>
                                        <td><?php echo $row['query_mail']; ?></td>
                                        <td><?php echo $row['query_number']; ?></td>
                                        <td><?php echo $row['query_message']; ?></td>
                                        <td><?php echo $row['query_date']; ?></td>
                                        <td><a href="javascript:void(0);" onclick="updateStatus(<?php echo $row['query_id']; ?>);">Pending</a></td>
                                        <td id="he" style="width:100px">
                                            <a style="background-color:aqua" href='delete_query.php?id=<?php echo $row['query_id']; ?>' onclick="return confirm('Are you sure you want to delete this query?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo '<tr><td colspan="8">No pending queries found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive" style="text-align:center; align:center">
                    <?php
                    // Pagination logic
                    $sql1 = "SELECT * FROM contact_query WHERE query_status = 0";
                    $result1 = mysqli_query($conn, $sql1);
                    $total_records = mysqli_num_rows($result1);
                    $total_page = ceil($total_records / $limit);

                    echo '<ul class="pagination admin-pagination">';
                    if ($page > 1) {
                        echo '<li><a href="pending_queries.php?page=' . ($page - 1) . '">Prev</a></li>';
                    }
                    for ($i = 1; $i <= $total_page; $i++) {
                        $active = ($i == $page) ? "active" : "";
                        echo '<li class="' . $active . '"><a href="pending_queries.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                    if ($total_page > $page) {
                        echo '<li><a href="pending_queries.php?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    echo '</ul>';
                    ?>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<div class="alert alert-danger"><b>Please Login First To Access Admin Portal.</b></div>';
        ?>
        <form method="post" name="" action="login.php" class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-4" style="float:left">
                    <button class="btn btn-primary" name="submit" type="submit">Go to Login Page</button>
                </div>
            </div>
        </form>
    <?php }
    ?>
</body>
</html>
