<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Application</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="app/css/app.css"> -->
</head>
<body>
    <section class="container"">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="h1 display-6 fw-bold text-center my-3">Food Bank Volunteer Application Form</h2>
                <nav class="mb-2">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link w-50 active" id="nav-personal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal" type="button" role="tab" aria-controls="nav-personal" aria-selected="true">01. Personal</button>
                        <button class="nav-link w-50" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">02. Contact</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- 01. PERSONAL -->
                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab" tabindex="0">
                        <form action="">
                            <label class="form-label mb-1" for="firstname"><strong>Name</strong></label>
                            <div class="row g-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="">
                                        <label class="form-label" for="firstname">First Name</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="">
                                        <label class="form-label" for="lastname">Last Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <label class="form-label mb-1" for="birthdate"><strong>Date of Birth</strong></label>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="">
                                        <input type="date" class="form-control form-control-lg" name="birthdate" id="birthdate" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <label class="form-label mb-1" for="address1"><strong>Address</strong></label>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" name="address1" id="address1" placeholder="">
                                        <label class="form-label" for="lastname">Street Address</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" name="address2" id="address2" placeholder="">
                                        <label class="form-label" for="lastname">Street Address Line 2</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="city" id="city" placeholder="">
                                        <label class="form-label" for="city">City</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="region" id="region" placeholder="">
                                        <label class="form-label" for="region">Region</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="">
                                        <label class="form-label" for="zipcode">Postal/Zip Code</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="country" id="country" placeholder="">
                                        <label class="form-label" for="country">Country</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-8 col-lg-4">
                                    <button type="button" class="btn btn-lg btn-success w-100" id="btnNext">Next</button>
                                </div>
                            </div>
                    </div>
                    <!-- END PERSONAL -->
                    <!-- 02. CONTACT -->
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                    <div class="row my-2">
                        <label class="form-label mb-1" for="contact"><strong>Contact Number</strong></label>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-lg" name="contact" id="contact" placeholder="">
                                    <label class="form-label" for="contact">Tel No. / Mobile No.</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <label class="form-label mb-1" for="email"><strong>Email Address</strong></label>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-lg" name="email" id="email" placeholder="">
                                    <label class="form-label" for="email">e.g. juan.delacruz@gmail.com</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12">
                                <label class="form-label mb-1"><strong>Terms of Volunteering</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="ysnTerms">
                                    <label class="form-check-label" for="ysnTerms">
                                        I agree to the terms of volunteering.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                <label class="form-label" for="signature"><strong>Select Signature</strong> (Image: JPG/PNG)</label>
                                <input class="form-control" type="file" id="signature" name="signature">
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12">
                                <label class="form-label" for="signature"><strong>Verification</strong></label>
                                Captcha
                            </div>
                        </div>
                        <div class="row g-2 justify-content-center">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <button type="button" class="btn btn-lg btn-outline-success w-100" id="btnPrev">Previous</button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" id="btnApply">Apply</button>
                            </div>
                        </div>
                        <div class="row text-center mt-5">
                            <div class="col-12">
                                <p>Never submit sensitive information such as passwords. <a href="javascript:void(0)">Report abuse</a></p>
                            </div>
                        </div>
                    </form>
                    </div>
                    <!-- END CONTACT -->
                </div>
            </div>
        </div>
    </section>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="app/js/volunteerForm.js"></script>
</body>
</html>