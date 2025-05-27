<?php include 'header.php'; ?>


<div class="container">
    <h3 class="table-title float-start">List of Students</h3>
    <h3 class="float-start ms-5">BLOCK5</h3>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Student-ID</th>
            <th>Course</th>
            <th>Action</th>
            <th>Attendance</th>
        </tr>
        </thead>
        <td>
        <?php

        $sql = "SELECT * FROM students";
        $res = mysqli_query($conn, $sql);

        if(mysqli_num_rows($res) > 0)
        {
            $counter = 1;
         while ($row = mysqli_fetch_assoc($res)): 
            $block = $row["block"];
            $id = $row['id'];
         ?>
          <?php 
             if($block == 'block5')
             {
                 ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['studentid']; ?></td>
                    <td><?php echo $row['course']; ?></td>

                    <td>
                    <div class="action-container">
                        <a class="action-btn edit-btn" href="update.php?id=<?php echo $row['id'] ?>">Edit</a>
                        <a class="action-btn delete-btn" href="backend/block5_delete.php?id=<?php echo $row['id'];?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </div>
                    </td>
                    <td>
                        <form method="POST" action="backend/block5_attendance.php">
                            <label>
                            <input type="date" name="date" value="date" required>
                            <input class="form-check-input" type="radio" name="present" value="Present" required> Present
                            </label>
                            <label>
                            <input class="form-check-input" type="radio" name="present" value="Late" required> Late
                            </label>
                            <label>
                            <input class="form-check-input" type="radio" name="present" value="Absent" required> Absent
                            </label>
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input class="btn btn-success ms-3" type="submit" name="submit" id="">
                        </form>
                    </td>
                </tr>
                 <?php
             }
         ?>
         
            <?php endwhile; ?>

            <?php
        }
            ?>
                

        </tbody>
    </table>
<br><br>
</div>
<div class="container">
    <h3 class="table-title">Attendance-Result</h3>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Gender</th>
            <th>Student-ID</th>
            <th>Course</th>
            <th>Attendance</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <td>
        <?php

        $sql = "SELECT * FROM students";
        $res = mysqli_query($conn, $sql);

        if(mysqli_num_rows($res) > 0)
        {
            $counter = 1;
         while ($row = mysqli_fetch_assoc($res)): 
            $block = $row["block"];
            $attendance = $row['attendance'];
         ?>
          <?php 
             if($block == 'block5')
             {
                 ?>
                    
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['studentid']; ?></td>
                    <td><?php echo $row['course']; ?></td>
                    <?php
                    if($attendance=='Present'){
                        ?>

                        <td class="text-success"><b><?php echo $row['attendance']; ?></b></td>
                        <?php
                    }elseif($attendance=='Absent'){

                        ?>

                        <td class="text-danger"><b><?php echo $row['attendance']; ?></b></td>
                        <?php
                    }elseif($attendance=='Late'){
                        
                        ?>

                        <td class="text-warning"><b><?php echo $row['attendance']; ?></b></td>
                        <?php

                    }
                    ?>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        
                    </td>
                </tr>

                 <?php
             }
         ?>
         
            <?php endwhile; ?>

            <?php
        }
            ?>


        </tbody>
    </table>
    
</div>
</body>
</html>
