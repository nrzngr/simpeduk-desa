<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-12 col-md-5">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                </div>
                                <form class="user" method="post" action="<?= base_url('auth/register') ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3"">
                                            <input type=" text" class="form-control form-control-user" id="nik"
                                            placeholder="NIK" name="nik" value="<?= set_value('nik') ?>">
                                            <?= form_error('nik', '<small class="text-danger pl-3">', '</small>') ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="nama_pegawai"
                                                placeholder="Nama" name="nama_pegawai"
                                                value="<?= set_value('nama_pegawai') ?>">
                                            <?= form_error('nama_pegawai', '<small class="text-danger pl-3">', '</small>') ?>
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email"
                                            name="email" placeholder="Email Address" value="<?= set_value('email') ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" id="password"
                                                name="password" placeholder="Password"
                                                value="<?= set_value('password') ?>">
                                            <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user" id="password2"
                                                name="password2" placeholder="Repeat Password">
                                            <?= form_error('password2', '<small class="text-danger pl-3">', '</small>') ?>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn button btn-primary btn-user btn-block">
                                        Register Account
                                    </button>
                                    <hr>

                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/forgotPassword') ?>">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth') ?>">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>