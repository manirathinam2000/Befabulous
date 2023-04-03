<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function preview_image1() {
                var filePath = document.getElementById("upload_profile");
                // Allowing file type
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                if (!allowedExtensions.exec(filePath.value)) {
                    if(filePath.value === ''){
                        document.getElementById("error1").innerHTML =
                            "Profile picture field is required!";
                    }
                    else{
                        document.getElementById("error1").innerHTML =
                            "The file must be a file of type: jpg , jpeg , png , pdf";
                    }
                    document.getElementById("error1").style.color = "red";
                    filePath.value = "";
                    $("#img").remove();
                    $("#img_preview").append(
                                    $(" <span style='position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);opacity:0.7' class='w-full   text-center font-normal text-gray-400 text-4xl'><i class='fa-solid fa-user'></i></span>")
                                );
                    return false;
                } else {
                    var filePath = document.getElementById("upload_profile");
                    if (filePath.files.length > 0) {
                        for (var i = 0; i < filePath.files.length; i++) {
                            const fsize = filePath.files.item(i).size;
                            const file = Math.round(fsize / 1024);
                            if (file >= 5000) {
                                document.getElementById("error1").innerHTML =
                                    "File too Big, please select a file less than 5mb";
                                document.getElementById("error1").style.color = "red";
                                document.getElementById("error1").style.color = "red";
                                filePath.value = "";
                                $("#img").remove();
                                $("#img_preview").append(
                                    $(" <span style='position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);opacity:0.7' class='w-full   text-center font-normal text-gray-400 text-4xl'><i class='fa-solid fa-user'></i></span>")
                                );
                                return false;
                            } else {
                                document.getElementById("error1").innerHTML =
                                    "<b>" + file + "</b> KB";
                                document.getElementById("error1").style.color = "blue";
                                $('#img_preview').html('');
                                $("#img_preview").append(
                                    $("<img id='img' style='width: 150px;height: 150px;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);' class='rounded-full' src='" +
                                        URL.createObjectURL(event.target.files[i]) +
                                        "'><br> span")
                                );
                            }
                        }
                    }
                }
            }
</script>

<script>
    // get the input element
const input = document.getElementById('phone');

// add an oninput event listener to the input element
input.addEventListener('input', function() {
// regular expression to match a mobile number with country code
const mobileNumberRegex = /^\+\d{1,3}\d{6,14}$/;

// get the value of the input element
const inputValue = input.value;

// check if the input value matches the mobile number regex
if (!mobileNumberRegex.test(inputValue)) {
// if the input value does not match the mobile number regex, remove any non-numeric characters
input.value = inputValue.replace(/[^\d\+]/g, '');
}
});

</script>