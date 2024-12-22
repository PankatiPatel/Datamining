
<html>
    <?php include "dbconfig.php" ?>
    <h4> Pankati Patel  Assignment 1 CPS5721  </h4>

    <p> 
        The questions are based on the uszips table in the datamining database. 
        Please refer to this <a href = 'https://simplemaps.com/data/us-zips'>link </a> for the
        dataset explanations.
     
    </p>

    <p> 
        1. Views and tables in database play different roles in data mining. Please give two <br>
        &nbsp &nbsp real-world examples based on the attributes in the uszips table in details when to use a view and a table <br>
        &nbsp &nbsp for data mining. Your examples must specify the column names and utilize the features of view and tables. <br><br>
         
        &nbsp &nbsp &nbsp &nbsp 1. View should be used to sumaraize information from different tables to produce reports <br> 
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp the strcture of the base table is not being affected. It provides a subset of information from <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp the base table. <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp CREATE VIEW temp AS SELECT u.state_name, sum(u.zipcodes), sum(u.income_household_median), p.area<br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp FROM datamining.uszips u, HW1_patpanka p GROUP BY p.state_id; <br><br>

        &nbsp &nbsp &nbsp &nbsp 2. Tables are an object and hold information. A view holds tables into <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp one virtual memory while a table stores connection information and records. <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp data is calculated and stored in a table to create a base table.<br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp CREATE TABLE temp1 AS SELECT state_name, population, <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp SUM(population) * SUM(health_uninsured)/100 AS num_uninsured <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp FROM datamining.uszip GROUP BY state_id; <br> <br>

    </p> 

    <br> <br> 

    <p>
        2. Give 4 examples in real-world based on the attributes in the uszips table that show <br>
        &nbsp &nbsp  the difference between a SQL report and data mining. You need to specify the column names. <br><br>
        &nbsp &nbsp &nbsp &nbsp 1. SQL report: find total population and total zipcodes in each state <br>
        &nbsp &nbsp &nbsp &nbsp 2. SQL report: find the max income_household_median for each state <br>
        &nbsp &nbsp &nbsp &nbsp 3. Datamining: Find correlation between uninsured and education_college_ or_above <br>
        &nbsp &nbsp &nbsp &nbsp 4. Datamining: Find k-means between median_household_income and all the different <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp levels of education to which education level gets the most income.
    </p>

    <br><br>

    <p>
        3. Show the zipocde of the richest zipcode for each state based on Median household <br>
        &nbsp &nbsp  income. The output should have (zipcode, city, state, population, income) that sorted by the state name. <br><br>
            <?php

                echo   "CREATE VIEW tempQ1 AS <br>
                        SELECT state_name , max(income_household_median) as income <br>
                        FROM datamining.uszips u <br>
                        WHERE income_household_median IS NOT NULL <br>
                        GROUP BY state_id  <br><br>"; 

                echo   "CREATE VIEW 2023S_patpanka.vHW1_Q3 AS <br>
                        SELECT u.zip, u.city, u.state_name AS state, u.population, u.income_household_median AS income <br>
                        FROM datamining.uszips u, tempQ1 t <br>
                        WHERE u.state_name = t.state_name AND u.income_household_median = t.income <br>
                        ORDER BY u.state_name ASC <br> <br>"; 

                
                $sql3 = "SELECT * FROM 2023S_patpanka.vHW1_Q3";
                $result = mysqli_query($conn, $sql3);
                
                if($result)
                {
                    if(mysqli_num_rows($result) > 0 )
                    {
                        echo  "<TABLE border=1>
                                <TR>
                                    <TH>Zipcode</TH>
                                    <TH>City</TH>
                                    <TH>State</TH>
                                    <TH>Population</TH>
                                    <TH>Median_Income</TH>
                                </TR>";
                        while($row3 = mysqli_fetch_array($result))
                        {
                            $zip = $row3["zip"];
                            $city = $row3["city"];
                            $state = $row3["state"];
                            $population = $row3["population"];
                            $median_income = $row3["income"];

                            echo "<TR>
                                    <TD> $zip </TD>
                                    <TD> $city </TD>
                                    <TD> $state </TD> 
                                    <TD> $population </TD>
                                    <TD> $median_income </TD>
                                </TR> ";
            
                        }
                        echo "</TABLE>";
                    }
                    else 
                        echo "There are no records"; 

                }
                else 
                    echo "There is something wrong";


            

            ?>
    </p>

    <br><br>

    <p>
        4. Create a table HW1_xxxx with columns (state, population, NumofZipcode, <br>
        &nbsp &nbsp health_uninsured, ) that has the total population, the number of zip code (NumofZipcode), total <br>
        &nbsp &nbsp health_uninsured population, total education_college_or_above population of each state and DC. Note: <br>
        &nbsp &nbsp The original data of health_uninsured and education_college_or_above are ratios, NOT size. <br><br>

            <?php

                echo "CREATE TABLE HW1_patpanka AS <br>
                    SELECT state_id, <br> 
                            SUM(population) AS population , <br> 
                            COUNT(zip) AS num_zipcode, <br>
                            SUM(population) * SUM(health_uninsured)/100 AS num_uninsured, <br>
                            SUM(population) * SUM(education_college_or_above)/100 AS num_education_or_above <br>
                    FROM datamining.uszips u  <br>
                    GROUP BY state_id <br>";


            ?>
    </p>

    <p>
        5. Add a new column to your HW1_xxxx table - avg_income to store the average <br>
        &nbsp &nbsp Median household income of each state and DC.<br><br>

            <?php
                echo "ALTER TABLE 2023.2023S_patpanka.HW1_patpanka <br>
                    ADD avg_income FLOAT <br><br>";

                echo "UPDATE 2023.2023S_patpanka.HW1_patpanka AS b1, <br> ( SELECT b.state_id, avg(income_household_median) AS counted <br>
                                                                    FROM datamining.uszips  a <br>
                                                                    JOIN 2023S_patpanka.HW1_patpanka b ON a.state_id = b.state_id <br>
                                                                    GROUP BY state_id) AS b2 <bR>
                    SET b1.avg_income = b2.counted <br>
                    WHERE b1.state_id = b2.state_id <br> <br> ";
            ?>



    </p>


    <p>
        6. Add two more columns to your HW1_xxxx table - state name and area by loading <br>
        &nbsp &nbsp the data file US_state_data.csv. You may need to create a temporary table first. <br><br>

                <?php

                    echo "ALTER TABLE HW1_patpanka <br> 
                          ADD state_name VARCHAR(30), <br>
                          ADD area FLOAT <br><br>";


                    echo "CREATE TABLE area <br>
                          ( <br>
                            state_id CHAR(2), <br>
                            state_name VARCHAR(30),<br>
                            area FLOAT,<br>
                            PRIMARY KEY(state_id)<br>
                          )<br><br>";

                    echo "UPDATE HW1_patpanka AS b1, <br> (SELECT a.state_id, a.state_name, a.area <br> 
                                                      FROM 2023S_patpanka.area  a <br> 
                                                      JOIN datamining.HW1_patpanka b ON a.state_id = b.state_id) AS b2 <br> 
                          SET b1.state_name = b2.state_name, <br> 
                              b1.area = b2.area <br> 
                          WHERE b1.state_id = b2.state_id <br> <br> ";

                ?>
    </p>

    <p>
        7. Please display your HW1_xxx data with density for each state and DC on the <br>
        &nbsp &nbsp browser. Note: density = population/area. The result should sorted by the state name. <br><br>

                <?php

                    echo "SELECT *,(population/area) AS density <br>
                          FROM 2023S_patpanka.HW1_patpanka <br>
                          ORDER BY state_name <br><br>";

                    $sql7 = "SELECT *,(population/area) AS density FROM 2023S_patpanka.HW1_patpanka ORDER BY state_name";
                    $result7 = mysqli_query($conn,$sql7); 

                    if($result7)
                    {
                        if(mysqli_num_rows($result7) > 0)
                        {
                            echo "<TABLE border = 1 >
                                  <TR>
                                        <TH> State_ID </TH>
                                        <TH> State Name </TH>
                                        <TH> Population </TH>
                                        <TH> Num_Zipcodes </TH>
                                        <TH> Num_Uninsured </TH>
                                        <TH> Num_Education_College_or_Above </TH>
                                        <TH> Avg_Median_Income </TH>
                                        <TH> Area </TH>
                                        <TH> Density </TH>
                                  </TR>";
                            
                            while($row7 = mysqli_fetch_array($result7))
                            {
                                $state_id7 = $row7 ["state_id"];
                                $population7 = $row7 ["population"]; 
                                $num_zipcode = $row7 ["num_zipcode"];
                                $num_uninsured = $row7 ["num_uninsured"];
                                $num_education = $row7 ["num_education_or_above"];
                                $avg_median_income = $row7 ["avg_income"];
                                $state_name = $row7 ["state_name"];
                                $area= $row7 ["area"];
                                $density = $row7 ["density"];


                                echo "<TR>
                                        <TD> $state_id7 </TD>
                                        <TD> $state_name </TD>
                                        <TD> $population7 </TD>
                                        <TD> $num_zipcode </TD>
                                        <TD> $num_uninsured </TD>
                                        <TD> $num_education</TD>
                                        <TD> $avg_median_income</TD>
                                        <TD> $area </TD>
                                        <TD> $density </TD>
                                      </TR>";
                            }

                            echo "</TABLE>";

                        }
                        else   
                            echo "There are no records";
                    }
                    else 
                        echo "There is an error";

                ?>

    </p>

    <p>
        
        8. Select 3 attributes (A, B, C) from your HW1 table, and identify which two attributes might be most related<br>
        &nbsp &nbsp based on the correlation values among the 50 states and DC.<br>

        &nbsp &nbsp &nbsp a. ________ You need to calculate the correlation values in the program and show each <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp correlation value for the 3 combinations (A, B), (B, C), (A, C). Please replace A, B and C with real <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp attribute names. Do NOT hardcode the values. <br>

        &nbsp &nbsp &nbsp b. ________ What is your conclusion? <br>

        &nbsp &nbsp &nbsp c. ________ Please explain your conclusion why these 2 attributes are most related and <br>
        &nbsp &nbsp &nbsp &nbsp &nbsp does it make sense in the real-world. <br><br>

        Note: Created my own correlation function the php one was giving back errors.<br><br>

                    <?php

                        $uninsured_arr = array();
                        $education_arr = array();
                        $avg_income_arr = array();
                        $sql8 = "SELECT num_uninsured, 
                                          num_education_or_above AS education, 
                                          avg_income AS median_income
                                  
                                 FROM 2023S_patpanka.HW1_patpanka 
                                 WHERE population is not null ";

                        $result8 = mysqli_query($conn, $sql8); 

                        if($result8)
                        {
                            if(mysqli_num_rows($result8) >0) 
                            {
                                while($row8 = mysqli_fetch_assoc($result8))
                                {
                                    $pop = $row8["num_uninsured"];
                                    array_push($uninsured_arr, $pop);

                                    $edu = $row8["education"];
                                    array_push($education_arr, $edu);

                                    $income = $row8["median_income"];
                                    array_push($avg_income_arr, $income);

                                
                                }

                                echo "<br>A. The three categories choosen are: Eudcation college or above, number_uninsured, Median Household Income";

                                echo "<br><br>Correlation value of number uninsured, number education college or above  ";
                                correlation($uninsured_arr, $education_arr);

                        
                                echo "<br> Correlation value of number of education college or above and average median household income  ";
                                correlation($education_arr, $avg_income_arr);

                                echo "<br> Correlation value of number uninsured and average median household income  " ;
                                correlation($uninsured_arr, $avg_income_arr);

                                echo "<br><br>";


                                echo "B. There is a correraltion between number of people uninsured and the number of people with an education  <br> 
                                      at college level or above. There /urlis a low colleration between the number of people with a college level or <br>
                                      higher education and the average median house hold income. There is an even lower relationship between the <br>
                                      number of uninsured peopple with the average household income.";

                                echo "C. There is a colleration between the number of people uninsured and the number of people with a college <br>
                                      education or higher. These two categories are the most related and this can hold true becasue people with a higher <br>
                                      education might suffer less illness or sickness. Theu would be more educated in health and food nutrients which can mean <br> 
                                      these people do not need to be insured. <br><br> 

                                      Evidence found <a href = 'https://dlib.bc.edu/islandora/object/bc-ir:100442/datastream/PDF/view'> here </a> shows that <br>
                                      there is a correlation between education and the number of people enrolled in health insurance. The higher the education <br> 
                                      the higher the insured rate is. The relationship between education and uninsured does not make sense. In the real world, people <br>
                                      with higher education tend to get good jobs that provide health insurance. Hence the correlation between people uninsured with <br>
                                      the number of people with an education at the college level or highr does not make sense.";
                               
                            }
                            else 
                                echo "There are no records";
                        }
                        else 
                            echo "There is something wrong". mysqli_error(); 




                        function correlation($x, $y)
                        {
                            // calculate mean 
                            
                            $x_total = 0;
                            $x_difference_mean = array();
                            $x_mean_squared = array();
                            $x_squared_sum = 0;
                            $y_total = 0;
                            $y_difference_mean = array();
                            $y_mean_squared = array(); 
                            $y_squared_sum = 0;
                            $mean_difference = array();
                            $mean_difference_sum = 0;
                            $coefficient = 0;

                            foreach($x as $xVal)
                            {
                                $x_total += $xVal; 
                            }
                            foreach($y as $yVal)
                            {
                                $y_total += $yVal; 
                            }

                            $x_mean = $x_total/sizeof($x);
                            $y_mean = $y_total/sizeof($y);

                         
                            // diffeence means
                            for ($i = 0; $i < sizeof($x); $i++)
                            {
                                // x and y mean difference
                                array_push($x_difference_mean, ($x[$i] - $x_mean));
                                array_push($y_difference_mean, ($y[$i] - $y_mean));

                           
                                // product of x and y mean difference
                                array_push($mean_difference, ($x_difference_mean[$i] * $y_difference_mean[$i]));

                                // sum of x and y mean differnce poduct
                                $mean_difference_sum += $mean_difference[$i];

                                // x and y mean squaed
                                array_push($x_mean_squared, (pow($x_difference_mean[$i], 2 )));
                                array_push($y_mean_squared, (pow($y_difference_mean[$i], 2 )));

                                // x and y mean squared sum
                                $x_squared_sum += $x_mean_squared[$i];
                                $y_squared_sum += $y_mean_squared[$i];

    
                            }
                           
                            $coefficient = $mean_difference_sum / (sqrt($x_squared_sum * $y_squared_sum));
                            echo $coefficient;
                        }
                    ?>

    </p>

</html>