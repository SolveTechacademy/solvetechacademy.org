<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <div class="row g-3">

            <div class="col-lg-4">
                <label class="form-label fw-semibold">Search Student</label>
                <input type="text"
                       id="studentSearch"
                       class="form-control"
                       placeholder="Search by Name, Email or Student ID">
            </div>

            <div class="col-lg-2">
                <label class="form-label fw-semibold">Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option>Active</option>
                    <option>Pending</option>
                    <option>Suspended</option>
                    <option>Graduated</option>
                </select>
            </div>

            <div class="col-lg-2">
                <label class="form-label fw-semibold">Country</label>
                <input type="text"
                       id="countryFilter"
                       class="form-control"
                       placeholder="Country">
            </div>

            <div class="col-lg-2">
                <label class="form-label fw-semibold">Qualification</label>
                <input type="text"
                       id="qualificationFilter"
                       class="form-control"
                       placeholder="Qualification">
            </div>

            <div class="col-lg-2">
                <label class="form-label fw-semibold">Sort By</label>
                <select id="sortFilter" class="form-select">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="az">A - Z</option>
                    <option value="za">Z - A</option>
                </select>
            </div>

        </div>

    </div>

</div>