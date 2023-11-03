                <div>
                    <div id="gallery" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                        <svg id="empty" class="bd-placeholder-img img-thumbnail" width="96px" height="120px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Responsive image" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <image href="" height="96" width="96"/>
                            <text x="50%" y="50%" fill="#dee2e6" dy=".3em">+</text>
                        </svg>
                    </div>
                </div>

                <template id="image-template" class="display-none">
                    <li style="display:inline-block; width:96px; height:96px">
                        <article tabindex="0" class="group hasImage w-full h-full rounded-md focus:outline-none focus:shadow-outline bg-gray-100 cursor-pointer relative text-transparent hover:text-white shadow-sm">
                            <img alt="upload preview" class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" />
                            <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                                <h1 class="flex-1"></h1>
                                <div class="flex">
                                    <span class="p-1">
                                        <i>
                                        <svg class="fill-current w-4 h-4 ml-auto pt-" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M5 8.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zm9 .5l-2.519 4-2.481-1.96-4 5.96h14l-5-8zm8-4v14h-20v-14h20zm2-2h-24v18h24v-18z" />
                                        </svg>
                                        </i>
                                    </span>

                                    <p class="p-1 size text-xs"></p>
                                    <button class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md">
                                        <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                                        </svg>
                                    </button>
                                </div>
                            </section>
                        </article>
                    </li>
                </template>

                <script>
                    const fileTempl = document.getElementById("file-template"),
                        imageTempl = document.getElementById("image-template"),
                        empty = document.getElementById("empty");

                    // use to store pre selected files
                    let FILES = {};

                    // check if file is of type image and prepend the initialied
                    // template to the target element
                    function addFile(target, file) {
                        const isImage = file.type.match("image.*"),
                            objectURL = URL.createObjectURL(file);

                        const clone = isImage
                            ? imageTempl.content.cloneNode(true)
                            : fileTempl.content.cloneNode(true);

                        clone.querySelector("h1").textContent = file.name;
                        clone.querySelector("li").id = objectURL;
                        clone.querySelector(".delete").dataset.target = objectURL;
                        clone.querySelector(".size").textContent =
                            file.size > 1024
                            ? file.size > 1048576
                                ? Math.round(file.size / 1048576) + "mb"
                                : Math.round(file.size / 1024) + "kb"
                            : file.size + "b";

                        isImage &&
                            Object.assign(clone.querySelector("img"), {
                            src: objectURL,
                            alt: file.name
                            });

                        empty.classList.remove("display-none");
                        empty.classList.add("hidden");
                        target.append(clone);

                        FILES[objectURL] = file;
                    }

                    const gallery = document.getElementById("gallery"),
                        overlay = document.getElementById("overlay");

                    // click the hidden input of type file if the visible button is clicked
                    // and capture the selected files
                    const hidden = document.getElementById("hidden-input");
                    console.log(hidden);
                    document.getElementById("empty").onclick = () => hidden.click();
                    hidden.onchange = (e) => {
                        console.log(e);
                        console.log(e.target.files);
                        for (const file of e.target.files) {
                            addFile(gallery, file);
                        }
                    };

                    // use to check if a file is being dragged
                    const hasFiles = ({ dataTransfer: { types = [] } }) =>
                      types.indexOf("Files") > -1;

                    // use to drag dragenter and dragleave events.
                    // this is to know if the outermost parent is dragged over
                    // without issues due to drag events on its children
                    let counter = 0;

                    // reset counter and append file to gallery when file is dropped
                    function dropHandler(ev) {
                        ev.preventDefault();
                        for (const file of ev.dataTransfer.files) {
                            addFile(gallery, file);
                            overlay.classList.remove("draggedover");
                            counter = 0;
                        }
                    }

                    // only react to actual files being dragged
                    function dragEnterHandler(e) {
                        e.preventDefault();
                        if (!hasFiles(e)) {
                            return;
                        }
                        ++counter && overlay.classList.add("draggedover");
                    }

                    function dragLeaveHandler(e) {
                        1 > --counter && overlay.classList.remove("draggedover");
                    }

                    function dragOverHandler(e) {
                        if (hasFiles(e)) {
                            e.preventDefault();
                        }
                    }

                    // event delegation to caputre delete events
                    // fron the waste buckets in the file preview cards
                    gallery.onclick = ({ target }) => {
                        if (target.classList.contains("delete")) {
                            const ou = target.dataset.target;
                            document.getElementById(ou).remove(ou);
                            gallery.children.length === 1 && empty.classList.remove("hidden");
                            delete FILES[ou];
                        }
                    };

                </script>
