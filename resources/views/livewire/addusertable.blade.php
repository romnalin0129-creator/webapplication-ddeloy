<div>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0 fs-3">การแก้ไข</h1>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Forms</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Validation</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-4">
                    <!-- Custom validation -->
                    <div class="col-lg-6">
                        <div class="card card-info card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Edit user</div>
                            </div>
                            <form wire:submit.prevent="updateuser" class="needs-validation" novalidate>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="validationCustom01" class="form-label">Name</label>
                                            <input
                                                type="text"
                                                wire:model="name"
                                                class="form-control"
                                                id="validationCustom01"
                                                required />
                                            <div class="valid-feedback">Looks good!</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="validationCustom02" class="form-label">Email</label>
                                            <input
                                                type="email"
                                                wire:model="email"
                                                class="form-control"
                                                id="validationCustom02"
                                                required />
                                            <div class="valid-feedback">Looks good!</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="validationCustomUsername" class="form-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input
                                                    type="password"
                                                    wire:model="password"
                                                    class="form-control"
                                                    id="validationCustomUsername"
                                                    aria-describedby="inputGroupPrepend"
                                                    required />
                                                <div class="invalid-feedback">Please choose a password.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-info" type="submit">Submit form</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Browser-default validation + tooltips -->
                </div>
            </div>
        </div>
    </main>
    
</div>