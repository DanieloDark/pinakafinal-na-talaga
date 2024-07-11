<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpiwebsite";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Borrower's Personal Information
    $borr_first = $_POST['borr_first'] ?? '';
    $borr_middle = $_POST['borr_middle'] ?? '';
    $borr_last = $_POST['borr_last'] ?? '';
    $borr_name = $borr_first . " " . $borr_middle . " " . $borr_last;
    $borr_dob = $_POST['borr_dob'] ?? '';
    $borr_age = intval($_POST['borr_age'] ?? 0);
    $borr_gender = $_POST['borr_gender'] ?? '';
    $borr_birth_place = $_POST['borr_birth_place'] ?? '';
    $borr_nationality = $_POST['borr_nationality'] ?? '';
    $borr_citizenship = $_POST['borr_citizenship'] ?? '';
    $borr_marital_status = $_POST['borr_marital_status'] ?? '';
    $borr_mother = $_POST['borr_mother'] ?? '';
    $borr_present_address = $_POST['borr_present_address'] ?? '';
    $borr_residency = intval($_POST['borr_residency'] ?? 0);
    $borr_permanent_address = $_POST['borr_permanent_address'] ?? '';
    $borr_previous_address = $_POST['borr_previous_address'] ?? '';
    $borr_home_ownership = $_POST['borr_home_ownership'] ?? '';
    $borr_children_ages = $_POST['borr_children_ages'] ?? '';
    $borr_dependents = intval($_POST['borr_dependents'] ?? 0);
    $borr_sssno = $_POST['borr_sssno'] ?? '';
    $borr_tin = $_POST['borr_tin'] ?? '';
    $borr_residencephone = $_POST['borr_residencephone'] ?? '';
    $borr_phoneno = $_POST['borr_phoneno'] ?? '';
    $borr_mailingaddress = $_POST['borr_mailingaddress'] ?? '';
    $borr_email = $_POST['borr_email'] ?? '';

    // Spouse information (if married)
    $sp_name = $sp_dob = $sp_age = $sp_birth_place = $sp_nationality = $sp_citizenship = $sp_mothers_maiden_name = $sp_sssno = $sp_tin = $sp_residence_phone = $sp_mobilephone = $sp_email = null;
    if ($borr_marital_status == 'married') {
        $sp_first = $_POST['sp_first'] ?? '';
        $sp_middle = $_POST['sp_middle'] ?? '';
        $sp_last = $_POST['sp_last'] ?? '';
        $sp_name = $sp_first . " " . $sp_middle . " " . $sp_last;
        $sp_dob = $_POST['sp_dob'] ?? '';
        $sp_age = intval($_POST['sp_age'] ?? 0);
        $sp_birth_place = $_POST['sp_birth_place'] ?? '';
        $sp_nationality = $_POST['sp_nationality'] ?? '';
        $sp_citizenship = $_POST['sp_citizenship'] ?? '';
        $sp_mothers_maiden_name = $_POST['sp_mothers_maiden_name'] ?? '';
        $sp_sssno = $_POST['sp_sssno'] ?? '';
        $sp_tin = $_POST['sp_tin'] ?? '';
        $sp_residence_phone = $_POST['sp_residence_phone'] ?? '';
        $sp_mobilephone = $_POST['sp_mobilephone'] ?? '';
        $sp_email = $_POST['sp_email'] ?? '';
    }

    // Insert borrower and spouse information
    $sql = "INSERT INTO borrower_information (borr_first, borr_middle, borr_last, borr_name, borr_dob, borr_age, borr_gender, borr_birth_place, borr_nationality, borr_citizenship, borr_marital_status, borr_mother, borr_present_address, borr_residency, borr_permanent_address, borr_previous_address, borr_home_ownership, borr_children_ages, borr_dependents, borr_sssno, borr_tin, borr_residencephone, borr_phoneno, borr_mailingaddress, borr_email, sp_first, sp_middle, sp_last, sp_name, sp_dob, sp_age, sp_birth_place, sp_nationality, sp_citizenship, sp_mothers_maiden_name, sp_sssno, sp_tin, sp_residence_phone, sp_mobilephone, sp_email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssississsssisssisissssssssssissssssss", $borr_first, $borr_middle, $borr_last, $borr_name, $borr_dob, $borr_age, $borr_gender, $borr_birth_place, $borr_nationality, $borr_citizenship, $borr_marital_status, $borr_mother, $borr_present_address, $borr_residency, $borr_permanent_address, $borr_previous_address, $borr_home_ownership, $borr_children_ages, $borr_dependents, $borr_sssno, $borr_tin, $borr_residencephone, $borr_phoneno, $borr_mailingaddress, $borr_email, $sp_first, $sp_middle, $sp_last, $sp_name, $sp_dob, $sp_age, $sp_birth_place, $sp_nationality, $sp_citizenship, $sp_mothers_maiden_name, $sp_sssno, $sp_tin, $sp_residence_phone, $sp_mobilephone, $sp_email);
    
    if ($stmt->execute()) {
        $borrower_id = $stmt->insert_id;

        // Financial Information (for borrower and spouse if married)

        $financial_data = [
            ['type' => 'borrower', 'prefix' => 'borr_']
        ];

        if ($borr_marital_status == 'married') {
            $financial_data[] = ['type' => 'spouse', 'prefix' => 'sp_'];
        }

        foreach ($financial_data as $data) {
            $prefix = $data['prefix'];

            $emp_type = $_POST[$prefix . 'emp_type'] ?? '';
            $emp_businessname = $_POST[$prefix . 'emp_businessname'] ?? '';
            $emp_offadd = $_POST[$prefix . 'emp_offadd'] ?? '';
            $emp_telephoneno = $_POST[$prefix . 'emp_telephoneno'] ?? '';
            $emp_nature = $_POST[$prefix . 'emp_nature'] ?? '';
            $emp_jobtitle = $_POST[$prefix . 'emp_jobtitle'] ?? '';
            $emp_datehired = $_POST[$prefix . 'emp_datehired'] ?? '';
            $emp_prev = $_POST[$prefix . 'emp_prev'] ?? '';
            $emp_prevtitle = $_POST[$prefix . 'emp_prevtitle'] ?? '';
            $emp_years = floatval($_POST[$prefix . 'emp_years'] ?? 0);
            $emp_gas = floatval($_POST[$prefix . 'emp_gas'] ?? 0);
            $emp_allowance = floatval($_POST[$prefix . 'emp_allowance'] ?? 0);
            $emp_busi_inc = floatval($_POST[$prefix . 'emp_busi_inc'] ?? 0);
            $emp_comm = floatval($_POST[$prefix . 'emp_comm'] ?? 0);
            $emp_rental_inc = floatval($_POST[$prefix . 'emp_rental_inc'] ?? 0);
            $emp_other = floatval($_POST[$prefix . 'emp_other'] ?? 0);
            $emp_total = floatval($_POST[$prefix . 'emp_total'] ?? 0);

            $financial_sql = "INSERT INTO financial_information (borrower_id, emp_type, emp_businessname, emp_offadd, emp_telephoneno, emp_nature, emp_jobtitle, emp_datehired, emp_prev, emp_prevtitle, emp_years, emp_gas, emp_allowance, emp_busi_inc, emp_comm, emp_rental_inc, emp_other, emp_total) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $financial_stmt = $conn->prepare($financial_sql);
            
            if ($financial_stmt === false) {
                echo "Debug: Error preparing statement: " . $conn->error . "<br>";
            } else {
                $financial_stmt->bind_param("isssssssssdddddddd", $borrower_id, $emp_type, $emp_businessname, $emp_offadd, $emp_telephoneno, $emp_nature, $emp_jobtitle, $emp_datehired, $emp_prev, $emp_prevtitle, $emp_years, $emp_gas, $emp_allowance, $emp_busi_inc, $emp_comm, $emp_rental_inc, $emp_other, $emp_total);
                
                if (!$financial_stmt->execute()) {
                    echo "Error inserting " . $data['type'] . " financial information: " . $financial_stmt->error . "<br>";
                } else {
                    echo "Debug: Successfully inserted " . $data['type'] . " financial information<br>";
                }
            }
        }

        // Existing Loans (multiple)
        if (isset($_POST['creditor_name']) && is_array($_POST['creditor_name'])) {
            foreach ($_POST['creditor_name'] as $index => $creditor_name) {
                $loan_type = $_POST['loan_type'][$index] ?? '';
                $maturity_date = $_POST['maturity_date'][$index] ?? '';
                $orig_loan_amt = floatval($_POST['orig_loan_amt'][$index] ?? 0);
                $outstanding_bal = floatval($_POST['outstanding_bal'][$index] ?? 0);
                $monthly_amortization = floatval($_POST['monthly_amortization'][$index] ?? 0);

                $loan_sql = "INSERT INTO existing_loan (borrower_id, creditor_name, loan_type, maturity_date, orig_loan_amt, outstanding_bal, monthly_amortization) VALUES (?,?,?,?,?,?,?)";
                $loan_stmt = $conn->prepare($loan_sql);
                $loan_stmt->bind_param("isssddd", $borrower_id, $creditor_name, $loan_type, $maturity_date, $orig_loan_amt, $outstanding_bal, $monthly_amortization);
                if (!$loan_stmt->execute()) {
                    echo "Error inserting existing loan: " . $loan_stmt->error;
                }
            }
        }

        // Deposit Accounts (multiple)
        if (isset($_POST['acc_number']) && is_array($_POST['acc_number'])) {
            foreach ($_POST['acc_number'] as $index => $acc_number) {
                $bnb_add = $_POST['bnb_add'][$index] ?? ''; 
                $acc_type = $_POST['acc_type'][$index] ?? '';

                $account_sql = "INSERT INTO deposit_account (acc_number, borrower_id, bnb_add, acc_type) VALUES (?,?,?,?)";
                $account_stmt = $conn->prepare($account_sql);
                if ($account_stmt === false) {
                    echo "Debug: Error preparing statement: " . $conn->error . "<br>";
                } else {
                    $account_stmt->bind_param("siss", $acc_number, $borrower_id, $bnb_add, $acc_type);
                    if (!$account_stmt->execute()) {
                        echo "Error inserting deposit account: " . $account_stmt->error . "<br>";
                    } else {
                        echo "Debug: Successfully inserted deposit account<br>";
                    }
                }
            }
        }

        echo "New request submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>