<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @if (isset($exeFileUpload))
        @if ($exeFileUpload == 1)
            @if (isset($exeAddTask))
                @if ($exeAddTask == 1)
                    <script>
                        Swal.fire({
                            title:'Task Added',
                            text:'The task has been added successfully',
                            icon:'success',
                            showConfirmButton:false,
                            willClose: () => {
                                window.location.href='/';
                            }
                        })
                    </script>
                @else
                    <script>
                        Swal.fire({
                            title:'Error Adding Task',
                            text:'There has been error in adding task',
                            icon:'error',
                            showConfirmButton:false,
                            willClose: () => {
                                window.location.href='/';
                            }
                        })
                    </script>
                @endif    
            @endif
        @else
            <script>
                Swal.fire({
                    title:'Invalid File Format',
                    text:'Please upload a file format only PDF',
                    icon:'error',
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @endif    
    @endif

    @if (isset($exeDeleteTask))
        @if ($exeDeleteTask == 1)
            <script>
                Swal.fire({
                    title:'Task Deleted',
                    text:'The task has been deleted successfully',
                    icon:'success',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @else
            <script>
                Swal.fire({
                    title:'Error Deleting Task',
                    text:'There has been error in deleting task',
                    icon:'error',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @endif
    @endif

    @if (isset($exeSignatureUpload))
        @if ($exeSignatureUpload == 1)
            @if (isset($exeAddSignature))
                @if ($exeAddSignature == 1)
                    <script>
                        Swal.fire({
                            title:'Signature Added',
                            text:'The signature has been added successfully',
                            icon:'success',
                            showConfirmButton:false,
                            willClose: () => {
                                window.location.href='/';
                            }
                        })
                    </script>
                @else
                    <script>
                        Swal.fire({
                            title:'Error Adding Signature',
                            text:'There has been error in adding signature',
                            icon:'error',
                            showConfirmButton:false,
                            willClose: () => {
                                window.location.href='/';
                            }
                        })
                    </script>
                @endif    
            @endif
        @else
            <script>
                Swal.fire({
                    title:'Invalid File Format',
                    text:'Please upload a file format only PNG',
                    icon:'error',
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @endif    
    @endif

    @if (isset($exeSignatureOnPdf))
        @if ($exeSignatureOnPdf == 1)
            <script>
                Swal.fire({
                    title:'Signature Place',
                    text:'The signature has been place in the file',
                    icon:'success',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @else
            <script>
                Swal.fire({
                    title:'Error Signature Place',
                    text:'There has been error in placing signature in the file',
                    icon:'error',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @endif
    @endif

    @if (isset($exeEditTask))
        @if ($exeEditTask == 1)
            <script>
                Swal.fire({
                    title:'Task Updated',
                    text:'The task has been updated successfully',
                    icon:'success',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @else
            <script>
                Swal.fire({
                    title:'Error Updating Task',
                    text: 'There has been error in updating task',
                    icon:'error',
                    timer:1500,
                    showConfirmButton:false,
                    willClose: () => {
                        window.location.href='/';
                    }
                })
            </script>
        @endif
    @endif

    {{-- Main Content --}}
    <br>

    <div style="display: flex; justify-content:center;">
        <div class="div" style="display: flex;">
            <div>
                <form action="/editTask/{{$id}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1 style="text-align: center; font-size:20px; font-weight:bold;">Task</h1><br>

                    <input type="text" placeholder="Enter a task" name="task" value="{{$checkTblRecord->task}}" class="form-control"><br>

                    <input type="file" name="filename" class="form-control"><br>

                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>

            <div style="margin-left: 30px">
                <form action="/addSignature" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1 style="text-align: center; font-size:20px; font-weight:bold;">Signature</h1><br>

                    <input type="text" placeholder="Enter a signature name" name="signature_name" class="form-control"><br>

                    <input type="file" name="signature_file" class="form-control"><br>

                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <br>
    <div style="display: flex; justify-content:center;">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center; height:50px; font-size:20px; background-color:black; color:white;">Task</th>

                    <th style="text-align: center; height:50px; font-size:20px; background-color:black; color:white;">Action</th>
                </tr>
            </thead>

            @foreach ($readTblRecord as $read_tbl_record)
            <tbody>
                <tr>
                    <td style="text-align: center; height:30px; font-size:17px;">{{$read_tbl_record->task}}</td>

                    <td style="text-align: center; height:30px; font-size:17px;">
                        <button class="btn btn-primary viewBtn" data-path="{{asset('storage/'.$read_tbl_record->path)}}">View File</button> 

                        <a href="/editTask/{{$read_tbl_record->id}}"><button class="btn btn-warning">Edit</button></a> 

                        <a href="/deleteTask/{{$read_tbl_record->id}}"><button class="btn btn-danger">Delete</button></a>
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div style="display: flex; justify-content: flex-end;"><span class="close">&times;</span></div>
            <br>

            <div>
                <iframe id="pdfFrame" src="" frameborder="0" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>
    <!-- The Modal End-->

    <script>
        var iframe = document.getElementById("pdfFrame");
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        // Loop through all view buttons
        var viewButtons = document.getElementsByClassName("viewBtn");
        for (let i = 0; i < viewButtons.length; i++) {
            viewButtons[i].onclick = function() {
                var path = this.getAttribute("data-path");
                iframe.src = path;
                modal.style.display = "block";
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>