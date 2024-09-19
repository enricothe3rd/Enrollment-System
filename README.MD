



















===========Registrar.php================

The section name must start with 1,2,3,4
1 = 1st Year
2 = 2nd Year
3 = 3rd Year
4 = 4th Year

The subject Code must start with 1, 2, S
1 = 1st semester
2 = 2nd semester
S = Summer

===========Registrar.php================








============Enrollment Proccess=========

-Enrollment Form
*all fields are required to be filled except middle name and suffix.
*Middle name if the student doesnt want to use they write  = "n/a or N/A".
*the user can  you back if they input the wrong details and must click the *save button again" to update the information.
they can unlimited update the data on enrollment Form.

-Validation of Enrollment Form
    *Last name must contain only letters and spaces.
    *First name must contain only letters and spaces.
    *Middle name must contain only letters or be "N/A
    *Date of birth must be in the format YYYY-MM-DD.
    *Address must be at least 5 characters long.
    *Contact number must be between 11 and 12 digits.
    *Select sex is required.
    *Select suffix is required or select N/A.
    *Select school year is required.
    *Select semester is required.
    *Select status is required.
============Enrollment Proccess=========













Cash Payment Process
1. Collection of Cash Payment
Payment Form

Create a form to capture the payment details (e.g., amount paid, date of payment).
Include fields for student ID or enrollment ID to link the payment to the correct student.
Receipt Issuance

Generate a receipt immediately upon receiving cash payment.
The receipt should include the amount paid, date, student information, and a unique receipt number.
2. Verification of Cash Payment
Manual Entry

Manually record the payment details in the database.
Verify that the payment amount matches the expected amount for the enrollment.
Update Records

Mark the payment as received in the system.
Update the student’s enrollment status based on the payment amount (e.g., “Partially Enrolled” or “Enrolled”).
3. Updating Enrollment Status
Full Payment

If the cash payment is equal to or greater than the total amount due, update the student’s status to “Enrolled.”
Record the payment details, including the receipt number.
Partial Payment

If the payment is less than the total amount due, update the status to “Pending” or “Partially Enrolled.”
Note the remaining balance and record the partial payment details.
4. Receipt and Confirmation
Send Receipt

Provide a copy of the receipt to the student, either physically or electronically if possible.
Ensure the receipt is clear and contains all necessary details.
Confirmation Email

Send an email confirming receipt of the payment.
Include payment details, receipt number, and updated enrollment status.
5. Record Maintenance
Payment History

Maintain a detailed log of all cash payments.
Include information on student ID, amount paid, date, receipt number, and status.
Security

Ensure that cash payment records are kept secure and are only accessible to authorized personnel.
Installment Payment Process
1. Setting Up Installment Plan
Payment Plan Options

Allow students to select from various installment options (e.g., monthly, quarterly).
Define the terms for each installment plan, including payment amounts, due dates, and total amount due.
Form Submission

Capture the installment plan selection and initial payment details in the form.
Generate a payment schedule based on the chosen plan.
2. Initial Payment Collection
Payment Form

Create a form to capture the initial installment payment.
Include fields for payment amount, date, and payment method.
Receipt Issuance

Issue a receipt for the initial payment, including details such as amount, date, and a unique receipt number.
3. Tracking and Verification of Installments
Automated Tracking

Set up automated tracking of installment payments and due dates.
Update the student’s payment status in the system after each payment is made.
Payment Verification

Confirm receipt of each installment payment.
Record payment details and update the remaining balance.
4. Updating Enrollment Status
Full Payment

If all installments are paid, update the student’s status to “Enrolled.”
Record the completion of the payment plan.
Partial Payment

After each installment, update the status to reflect the payment progress (e.g., “Installments Paid”).
Provide updated balances and next due dates.
5. Reminders and Notifications
Automated Reminders

Send automated reminders to students before each installment due date.
Include details of the upcoming payment amount and due date.
Confirmation Emails

Send confirmation emails after each installment payment is received.
Include payment details, updated balance, and next due date.
6. Receipts and Invoices
Generate Receipts

Issue receipts for each installment payment, including amount, date, and remaining balance.
Provide access to download or view receipts online.
Create Invoices

For the entire installment plan, create an invoice detailing the total amount due, payment schedule, and amounts paid.
7. Record Maintenance
Installment History

Maintain a detailed log of all installment payments, including student ID, amounts, dates, and payment status.
Security

Securely store all records of installment payments and ensure they are accessible only to authorized personnel.