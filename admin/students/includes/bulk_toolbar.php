<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div>

                <div class="form-check">

                    <input class="form-check-input"
                           type="checkbox"
                           id="selectAllStudents">

                    <label class="form-check-label fw-semibold"
                           for="selectAllStudents">

                        Select All

                    </label>

                </div>

            </div>

            <div class="btn-group">

                <button type="button"
                        class="btn btn-success"
                        id="activateSelected">

                    <i class="bi bi-check-circle-fill"></i>

                    Activate

                </button>

                <button type="button"
                        class="btn btn-warning"
                        id="suspendSelected">

                    <i class="bi bi-pause-circle-fill"></i>

                    Suspend

                </button>

                <button type="button"
                        class="btn btn-info"
                        id="exportSelected">

                    <i class="bi bi-download"></i>

                    Export

                </button>

                <button type="button"
                        class="btn btn-danger"
                        id="deleteSelected">

                    <i class="bi bi-trash-fill"></i>

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const selectAll = document.getElementById("selectAllStudents");

    if(selectAll){

        selectAll.addEventListener("change", function(){

            document.querySelectorAll(".student-checkbox").forEach(function(box){

                box.checked = selectAll.checked;

            });

        });

    }

});

</script>