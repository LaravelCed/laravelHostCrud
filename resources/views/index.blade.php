<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        .modal { display:none; position:fixed; z-index:1000; padding-top:50px; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.6); }
        .modal-content { background:#fff; margin:auto; padding:20px; border:1px solid #888; width:90%; max-width:1200px; border-radius:10px; position:relative; }
        .signature-img { position:absolute; width:150px; cursor:move; display:none; z-index:9999; pointer-events:auto; border:2px dashed #007bff; opacity:0.9; }
        #pdfContainer { position:relative; background:#525252; display:inline-block; margin:0 auto; }
        #pdfCanvas { display:block; background:white; box-shadow: 0 0 10px rgba(0,0,0,0.3); }
    </style>
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

    {{-- Main Content --}}
    <br>
    <div style="display:flex;justify-content:center;">
        <div class="div" style="display:flex;">
            <div>
                <form action="/addTask" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1 style="text-align:center;font-size:20px;font-weight:bold;">Task</h1><br>
                    <input type="text" placeholder="Enter a task" name="task" class="form-control"><br>
                    <input type="file" name="filename" class="form-control"><br>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
            <div style="margin-left:30px;">
                <form action="/addSignature" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1 style="text-align:center;font-size:20px;font-weight:bold;">Signature</h1><br>
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

                        <a href="/edit/{{$read_tbl_record->id}}"><button class="btn btn-warning">Edit</button></a> 

                        <a href="/deleteTask/{{$read_tbl_record->id}}"><button class="btn btn-danger">Delete</button></a>
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>

    <!-- For Signature Placing Function -->
    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <div style="display:flex;justify-content:flex-end;"><span class="close">&times;</span></div>
            <br>
            <div style="display:flex;align-items:center;gap:10px;">
                <form id="signatureForm" action="/addSignatureToPDF" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pdf_path" id="pdf_path">

                    <input type="hidden" name="pos_x_percent" id="pos_x_percent">

                    <input type="hidden" name="pos_y_percent" id="pos_y_percent">

                    <input type="hidden" name="page_number" id="page_number">

                    <input type="hidden" name="canvas_width" id="canvas_width">

                    <input type="hidden" name="canvas_height" id="canvas_height">

                    <select name="signature_id" id="signatureSelect" class="form-control" style="width:300px;">
                        <option value="" selected disabled>Select Signature</option>

                        @foreach ($readTblSignature as $sig)
                            <option value="{{$sig->signature_id}}" data-img="{{asset('storage/'.$sig->signature_path)}}">
                                {{$sig->signature_name}}
                            </option>
                        @endforeach
                    </select>

                    <select id="pageSelect" name="pageSelect" class="form-control" style="width:150px;">
                        <option value="1">Page 1</option>
                    </select>

                    <button type="submit" class="btn btn-success">Add Signature</button>
                </form>
            </div>
            <br>
            <div style="text-align:center;">
                <div id="pdfContainer">
                    <canvas id="pdfCanvas"></canvas>
                    
                    <img id="signaturePreview" class="signature-img" draggable="false">
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const modal = document.getElementById("myModal");
        const span = document.getElementsByClassName("close")[0];
        const sigPrev = document.getElementById("signaturePreview");
        const sigSelect = document.getElementById("signatureSelect");
        const pdfContainer = document.getElementById("pdfContainer");
        const canvas = document.getElementById("pdfCanvas");
        const ctx = canvas.getContext("2d");
        const form = document.getElementById("signatureForm");
        const pageSelect = document.getElementById("pageSelect");

        let pdfDoc = null;
        let currentPage = 1;
        let pageRendering = false;
        let scale = 1.5;

        // Render PDF page
        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({scale: scale});
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                document.getElementById("canvas_width").value = viewport.width;
                document.getElementById("canvas_height").value = viewport.height;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(function() {
                    pageRendering = false;
                });
            });
        }

        // Load PDF
        function loadPDF(url) {
            const loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                pdfDoc = pdf;
                
                // Populate page selector
                pageSelect.innerHTML = '';
                for (let i = 1; i <= pdf.numPages; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = 'Page ' + i;
                    pageSelect.appendChild(option);
                }
                
                renderPage(currentPage);
            });
        }

        // Change page
        pageSelect.addEventListener("change", function() {
            currentPage = parseInt(this.value);
            renderPage(currentPage);
            sigPrev.style.display = "none";
        });

        // Open modal
        document.querySelectorAll(".viewBtn").forEach(btn => {
            btn.addEventListener("click", function() {
                let path = this.getAttribute("data-path");
                document.getElementById("pdf_path").value = path;
                modal.style.display = "block";
                loadPDF(path);
            });
        });

        // Close modal
        span.onclick = () => { 
            modal.style.display = "none"; 
            sigPrev.style.display = "none";
            pdfDoc = null;
        };
        window.onclick = e => { 
            if (e.target == modal) { 
                modal.style.display = "none"; 
                sigPrev.style.display = "none";
                pdfDoc = null;
            } 
        };

        // Show signature preview
        sigSelect.addEventListener("change", function() {
            let selected = this.options[this.selectedIndex];
            let imgPath = selected.getAttribute("data-img");
            if (imgPath) {
                sigPrev.src = imgPath;
                sigPrev.style.display = "block";
                sigPrev.style.left = "50px";
                sigPrev.style.top = "50px";
            }
        });

        // Drag functionality
        let dragging = false;
        let startX = 0;
        let startY = 0;
        let initialLeft = 0;
        let initialTop = 0;
        
        sigPrev.addEventListener("mousedown", e => {
            dragging = true;
            startX = e.clientX;
            startY = e.clientY;
            initialLeft = sigPrev.offsetLeft;
            initialTop = sigPrev.offsetTop;
            sigPrev.style.cursor = "grabbing";
            e.preventDefault();
        });
        
        document.addEventListener("mousemove", e => {
            if (dragging) {
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                
                let newLeft = initialLeft + deltaX;
                let newTop = initialTop + deltaY;
                
                const containerWidth = canvas.width;
                const containerHeight = canvas.height;
                const sigWidth = sigPrev.offsetWidth;
                const sigHeight = sigPrev.offsetHeight;
                
                newLeft = Math.max(0, Math.min(newLeft, containerWidth - sigWidth));
                newTop = Math.max(0, Math.min(newTop, containerHeight - sigHeight));
                
                sigPrev.style.left = newLeft + "px";
                sigPrev.style.top = newTop + "px";
            }
        });
        
        document.addEventListener("mouseup", () => {
            if (dragging) {
                dragging = false;
                sigPrev.style.cursor = "move";
            }
        });

        // Submit coordinates
        form.addEventListener("submit", function(e) {
            if (sigPrev.style.display !== "none") {
                const canvasWidth = canvas.width;
                const canvasHeight = canvas.height;
                
                const sigLeft = sigPrev.offsetLeft;
                const sigTop = sigPrev.offsetTop;
                
                const xPercent = (sigLeft / canvasWidth) * 100;
                const yPercent = (sigTop / canvasHeight) * 100;
                
                document.getElementById("pos_x_percent").value = xPercent;
                document.getElementById("pos_y_percent").value = yPercent;
                document.getElementById("page_number").value = currentPage;
            }
        });
    </script>
    <!-- For Signature Placing Function End -->

</body>
</html>
