<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Funding Gateway - Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6c757d;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .registration-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .registration-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .registration-body {
            padding: 2rem;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6c757d;
            position: relative;
            z-index: 2;
        }
        
        .step.active {
            background: var(--primary);
            color: white;
        }
        
        .step.completed {
            background: var(--success);
            color: white;
        }
        
        .step-connector {
            height: 2px;
            background: #e9ecef;
            flex: 1;
            max-width: 80px;
        }
        
        .step-connector.completed {
            background: var(--success);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #3a59c7;
            border-color: #3a59c7;
        }
        
        .terms-modal .modal-content {
            border-radius: 10px;
            border: none;
        }
        
        .terms-modal .modal-header {
            background: var(--primary);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        .terms-content {
            max-height: 50vh;
            overflow-y: auto;
            padding: 10px;
        }
        
        .countdown {
            font-weight: bold;
            color: var(--primary);
        }
        
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Alert Container -->
        <div class="alert-container"></div>
        
        <!-- Main Registration Container -->
        <div class="registration-container">
            <div class="registration-header">
                <h3 class="mb-0">Academic Funding Gateway</h3>
                <p class="mb-0">Grant Registration Portal</p>
            </div>
            
            <div class="registration-body">
                <!-- Step 1: Phone Verification -->
                <div id="step-phone" class="registration-step">
                    <div class="step-indicator">
                        <div class="step active">1</div>
                        <div class="step-connector"></div>
                        <div class="step">2</div>
                        <div class="step-connector"></div>
                        <div class="step">3</div>
                    </div>
                    
                    <h4 class="text-center mb-4">Verify Your Phone Number</h4>
                    
                    <form id="phone-verification-form">
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone text-primary"></i>
                                </span>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       placeholder="Enter your registered phone number" required>
                            </div>
                            <div class="form-text">Enter the phone number you provided during data collection</div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right me-2"></i>Continue
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            Don't have your phone number registered? <a href="#">Contact support</a> for assistance.
                        </small>
                    </div>
                </div>
                
                <!-- Step 2: Profile Completion -->
                <div id="step-profile" class="registration-step" style="display: none;">
                    <div class="step-indicator">
                        <div class="step completed">1</div>
                        <div class="step-connector completed"></div>
                        <div class="step active">2</div>
                        <div class="step-connector"></div>
                        <div class="step">3</div>
                    </div>
                    
                    <h4 class="text-center mb-4">Complete Your Profile</h4>
                    
                    <form id="profile-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control" value="John" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control" value="Doe" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-phone text-primary"></i>
                                </span>
                                <input type="text" class="form-control" value="+2348012345678" disabled>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="johndoe@example.com" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="school" class="form-label">School/Institution</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-school text-primary"></i>
                                </span>
                                <input type="text" class="form-control" id="school" name="school" 
                                       value="University of Lagos" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="matriculation_number" class="form-label">Matriculation/Student Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-id-card text-primary"></i>
                                </span>
                                <input type="text" class="form-control" id="matriculation_number" 
                                       name="matriculation_number" value="UL/2019/1234">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </span>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="need_assessment_text" class="form-label">Need Assessment</label>
                            <textarea class="form-control" id="need_assessment_text" name="need_assessment_text" rows="5" 
                                      placeholder="Please describe why you need this grant and how it will help your academic/career goals (max 1000 characters)" 
                                      required></textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right me-2"></i>Continue to Payment
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Step 3: Payment -->
                <div id="step-payment" class="registration-step" style="display: none;">
                    <div class="step-indicator">
                        <div class="step completed">1</div>
                        <div class="step-connector completed"></div>
                        <div class="step completed">2</div>
                        <div class="step-connector completed"></div>
                        <div class="step active">3</div>
                    </div>
                    
                    <h4 class="text-center mb-4">Payment & Terms</h4>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Registration Fee:</strong> ₦3,000 (Non-refundable)
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-graduation-cap text-primary me-2"></i>Grant Value</h6>
                                    <p class="card-text">Up to ₦500,000 as full scholarship to training programs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-check-circle text-primary me-2"></i>Eligibility</h6>
                                    <p class="card-text">Subject to application review and acceptance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Transfer Details -->
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-university me-2"></i>Bank Transfer Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Account Number:</strong> <span class="text-primary fs-5">1028614880</span></p>
                                    <p class="mb-2"><strong>Bank Name:</strong> UBA (United Bank for Africa)</p>
                                    <p class="mb-0"><strong>Account Name:</strong> Academic Funding Gateway Network</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-0">
                                        <small><i class="fas fa-exclamation-triangle me-1"></i>
                                        <strong>Important:</strong> Please use your phone number as the transfer reference for easy identification.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form id="payment-form">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms_agreed" name="terms_agreed" required>
                            <label class="form-check-label" for="terms_agreed">
                                I have read and agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <!-- Payment Evidence Upload -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Payment Evidence</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="payment_evidence" class="form-label">Payment Receipt/Evidence</label>
                                    <input type="file" class="form-control" id="payment_evidence" name="payment_evidence" 
                                           accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div class="form-text">
                                        Upload a clear image (JPG, PNG) or PDF of your payment receipt. Maximum file size: 5MB
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="payment_note" class="form-label">Additional Notes (Optional)</label>
                                    <textarea class="form-control" id="payment_note" name="payment_note" rows="2" 
                                              placeholder="Add any additional information about your payment"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-success">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Processing Time:</strong> Your application will be reviewed within 24 hours after payment verification.
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Submit Payment Evidence
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-secondary" id="back-to-profile">
                            <i class="fas fa-arrow-left me-2"></i>Back to Profile
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Status -->
                <div id="step-status" class="registration-step text-center" style="display: none;">
                    <div class="status-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h4 class="text-success mb-3">Registration Complete!</h4>
                    
                    <p class="lead mb-4">
                        Thank you for completing your registration and submitting your payment evidence.
                    </p>
                    
                    <div class="alert alert-info mb-4">
                        <h6><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
                        <ul class="mb-0 ps-3 text-start">
                            <li><strong>Payment Verification:</strong> Your payment evidence will be verified within 24 hours</li>
                            <li><strong>Application Review:</strong> After payment verification, your application will be reviewed</li>
                            <li>You will receive an email notification about your application status</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning mb-4">
                        <h6><i class="fas fa-clock me-2"></i>Processing Timeline</h6>
                        <p class="mb-0">
                            <strong>Payment Verification:</strong> Within 24 hours<br>
                            <strong>Final Review:</strong> 2-3 business days after payment confirmation
                        </p>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6>Need Help?</h6>
                            <p class="mb-0">If you have any questions or concerns, please contact our support team.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p>You will be redirected to the homepage in <span class="countdown">5</span> seconds.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Terms and Conditions Modal -->
    <div class="modal fade terms-modal" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body terms-content">
                    <h6>Academic Funding Gateway - Terms and Conditions</h6>
                    
                    <p><strong>1. Grant Nature</strong></p>
                    <p>The Academic Funding Gateway provides grants in the form of paid access to approved training programs. Grants are not provided as cash payments but as full scholarships to partner institutions.</p>
                    
                    <p><strong>2. Registration Fee</strong></p>
                    <p>A non-refundable registration fee of ₦3,000 is required to complete the application process. This fee is not deductible from the grant amount.</p>
                    
                    <p><strong>3. Application Review</strong></p>
                    <p>All applications are subject to review. Acceptance is not guaranteed and depends on available slots, eligibility criteria, and assessment results. Review process takes up to 24 hours after payment verification.</p>
                    
                    <p><strong>4. Grant Utilization</strong></p>
                    <p>Accepted applicants must utilize their grants within the specified timeframe and at designated partner institutions.</p>
                    
                    <p><strong>5. Data Usage</strong></p>
                    <p>Personal information provided will be used for application processing and communication purposes only.</p>
                    
                    <p><strong>6. Modifications</strong></p>
                    <p>The organization reserves the right to modify these terms as necessary.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form navigation
            const steps = document.querySelectorAll('.registration-step');
            const phoneForm = document.getElementById('phone-verification-form');
            const profileForm = document.getElementById('profile-form');
            const paymentForm = document.getElementById('payment-form');
            const backToProfileBtn = document.getElementById('back-to-profile');
            
            // Show first step
            showStep(0);
            
            // Phone verification form submission
            phoneForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const phoneNumber = document.getElementById('phone_number').value;
                
                if (phoneNumber.length < 10) {
                    showAlert('Please enter a valid phone number', 'danger');
                    return;
                }
                
                showAlert('Phone number verified successfully!', 'success');
                showStep(1);
            });
            
            // Profile form submission
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('email').value;
                const school = document.getElementById('school').value;
                const address = document.getElementById('address').value;
                const needAssessment = document.getElementById('need_assessment_text').value;
                
                if (!email || !school || !address || !needAssessment) {
                    showAlert('Please fill in all required fields', 'danger');
                    return;
                }
                
                showAlert('Profile updated successfully!', 'success');
                showStep(2);
            });
            
            // Payment form submission
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!document.getElementById('terms_agreed').checked) {
                    showAlert('You must agree to the terms and conditions', 'danger');
                    return;
                }
                
                const paymentEvidence = document.getElementById('payment_evidence').files[0];
                if (!paymentEvidence) {
                    showAlert('Please upload payment evidence', 'danger');
                    return;
                }
                
                // Check file size
                const fileSize = paymentEvidence.size / 1024 / 1024; // Convert to MB
                if (fileSize > 5) {
                    showAlert('File size must be less than 5MB', 'danger');
                    return;
                }
                
                showAlert('Payment evidence submitted successfully! Your application will be reviewed within 24 hours.', 'success');
                showStep(3);
                
                // Start countdown for redirect
                let seconds = 5;
                const countdownElement = document.querySelector('.countdown');
                const countdown = setInterval(function() {
                    seconds--;
                    countdownElement.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        // In a real implementation, this would redirect to the actual landing page
                        showAlert('Redirecting to landing page...', 'info');
                        setTimeout(() => {
                            // For demo purposes, we'll just go back to the first step
                            document.getElementById('phone_number').value = '';
                            showStep(0);
                        }, 1500);
                    }
                }, 1000);
            });
            
            // Back to profile button
            backToProfileBtn.addEventListener('click', function() {
                showStep(1);
            });
            
            // Function to show specific step
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    if (index === stepIndex) {
                        step.style.display = 'block';
                    } else {
                        step.style.display = 'none';
                    }
                });
                
                // Scroll to top of form
                window.scrollTo(0, 0);
            }
            
            // File size validation
            document.getElementById('payment_evidence').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileSize = file.size / 1024 / 1024; // Convert to MB
                    if (fileSize > 5) {
                        showAlert('File size must be less than 5MB', 'danger');
                        e.target.value = '';
                    }
                }
            });
            
            // Function to show alerts
            function showAlert(message, type) {
                const alertContainer = document.querySelector('.alert-container');
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                alertContainer.appendChild(alert);
                
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>