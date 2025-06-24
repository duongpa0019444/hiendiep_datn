@extends('admin.admin')
@section('title', 'Quản lí người dùng')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid ">
             <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lí người dùng</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2">Học sinh</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $roleCounts['student'] }}</p>
                                </div>
                                <a href="{{ route('admin.account.list', 'student')}}">
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 128 128"><path fill="#edc391" d="M73.76 89.08H54.23v19.33c0 4.85 3.98 8.78 8.88 8.78h1.77c4.9 0 8.88-3.93 8.88-8.78zm17.57-38.67H36.67c-5.89 0-10.71 5.14-10.71 11.41c0 6.28 4.82 11.41 10.71 11.41h54.65c5.89 0 10.71-5.14 10.71-11.41c.01-6.27-4.81-11.41-10.7-11.41"/><path fill="#f9ddbd" d="M64 11.05c-17.4 0-33.52 18.61-33.52 45.39c0 26.64 16.61 39.81 33.52 39.81s33.52-13.17 33.52-39.81c0-26.78-16.12-45.39-33.52-45.39"/><g fill="#312d2d"><ellipse cx="47.56" cy="58.79" rx="4.93" ry="5.1"/><ellipse cx="80.44" cy="58.79" rx="4.93" ry="5.1"/></g><path fill="#dba689" d="M67.86 68.04c-.11-.04-.21-.07-.32-.08h-7.07c-.11.01-.22.04-.32.08c-.64.26-.99.92-.69 1.63s1.71 2.69 4.55 2.69s4.25-1.99 4.55-2.69c.29-.71-.06-1.37-.7-1.63"/><path fill="#444" d="M72.42 76.12c-3.19 1.89-13.63 1.89-16.81 0c-1.83-1.09-3.7.58-2.94 2.24c.75 1.63 6.45 5.42 11.37 5.42s10.55-3.79 11.3-5.42c.75-1.66-1.09-3.33-2.92-2.24"/><path fill="#312d2d" d="M64.02 5.03h-.04c-45.44.24-36.13 50.14-36.13 50.14s2.04 5.35 2.97 7.71c.13.34.63.3.71-.05c.97-4.34 4.46-19.73 6.22-24.41a6.075 6.075 0 0 1 6.79-3.83c4.46.81 11.55 1.81 19.38 1.81h.16c7.82 0 14.92-1 19.37-1.81c2.9-.53 5.76 1.08 6.79 3.83c1.75 4.66 5.22 19.96 6.2 24.36c.08.36.58.39.71.05l2.98-7.67c.02.01 9.32-49.89-36.11-50.13"/><radialGradient id="notoManStudentLightSkinTone0" cx="64.001" cy="81.221" r="37.873" gradientTransform="matrix(1 0 0 -1.1282 0 138.298)" gradientUnits="userSpaceOnUse"><stop offset=".794" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoManStudentLightSkinTone0)" d="M100.15 55.17s9.31-49.9-36.13-50.14h-.04c-.71 0-1.4.02-2.08.05c-1.35.06-2.66.16-3.92.31h-.04c-.09.01-.17.03-.26.04c-38.25 4.81-29.84 49.74-29.84 49.74l2.98 7.68c.13.34.62.31.7-.05c.98-4.39 4.46-19.71 6.22-24.37a6.08 6.08 0 0 1 6.8-3.83c4.46.8 11.55 1.8 19.38 1.8h.16c7.82 0 14.92-1 19.37-1.81c2.9-.53 5.76 1.08 6.79 3.83c1.76 4.68 5.25 20.1 6.21 24.42c.08.36.57.39.7.05c.94-2.35 3-7.72 3-7.72"/><path fill="#454140" d="M40.01 50.72c2.99-4.23 9.78-4.63 13.67-1.48c.62.5 1.44 1.2 1.68 1.98c.4 1.27-.82 2.26-2.01 1.96c-.76-.19-1.47-.6-2.22-.83c-1.37-.43-2.36-.55-3.59-.55c-1.82-.01-2.99.22-4.72.92c-.71.29-1.29.75-2.1.41c-.93-.39-1.27-1.57-.71-2.41m46.06 2.4c-.29-.13-.57-.29-.86-.41c-1.78-.74-2.79-.93-4.72-.92c-1.7.01-2.71.24-4.04.69c-.81.28-1.84.98-2.74.71c-1.32-.4-1.28-1.84-.56-2.76c.86-1.08 2.04-1.9 3.29-2.44c2.9-1.26 6.44-1.08 9.17.55c.89.53 1.86 1.26 2.4 2.18c.78 1.31-.4 3.03-1.94 2.4"/><path fill="#e8ad00" d="M116.5 54.28c-1.24 0-2.25.96-2.25 2.14v9.2c0 1.18 1.01 2.14 2.25 2.14s2.25-.96 2.25-2.14v-9.2c0-1.18-1.01-2.14-2.25-2.14m-4.5 0c-1.24 0-2.25.96-2.25 2.14v9.2c0 1.18 1.01 2.14 2.25 2.14s2.25-.96 2.25-2.14v-9.2c0-1.18-1.01-2.14-2.25-2.14"/><path fill="#ffca28" d="M114.25 54.28c-1.24 0-2.25.96-2.25 2.14v11.19c0 1.18 1.01 2.14 2.25 2.14s2.25-.96 2.25-2.14V56.42c0-1.18-1.01-2.14-2.25-2.14"/><ellipse cx="114.25" cy="53.05" fill="#ffca28" rx="2.76" ry="2.63"/><path fill="#504f4f" d="M114.25 53.02c-.55 0-1-.45-1-1v-38c0-.55.45-1 1-1s1 .45 1 1v38c0 .56-.45 1-1 1"/><linearGradient id="notoManStudentLightSkinTone1" x1="64" x2="64" y1="127.351" y2="98.71" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset=".003" stop-color="#424242"/><stop offset=".472" stop-color="#353535"/><stop offset="1" stop-color="#212121"/></linearGradient><path fill="url(#notoManStudentLightSkinTone1)" d="M116 12.98c-30.83-7.75-52-8-52-8s-21.17.25-52 8v.77c0 1.33.87 2.5 2.14 2.87c3.72 1.1 13.13 3.53 18.18 4.54c-.08.08-1.1 1.87-1.83 3.53c0 0 8.14 5.72 33.52 8.28c25.38-2.56 33.76-7.58 33.76-7.58c-.88-1.81-1.79-3.49-1.79-3.49c4.5-.74 14.23-4.07 17.95-5.26c1.25-.4 2.09-1.55 2.09-2.86v-.8z"/><linearGradient id="notoManStudentLightSkinTone2" x1="64" x2="64" y1="127.184" y2="96.184" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset=".003" stop-color="#616161"/><stop offset=".324" stop-color="#505050"/><stop offset=".955" stop-color="#242424"/><stop offset="1" stop-color="#212121"/></linearGradient><path fill="url(#notoManStudentLightSkinTone2)" d="M64 4.98s-21.17.25-52 8c0 0 35.41 9.67 52 9.38c16.59.29 52-9.38 52-9.38c-30.83-7.75-52-8-52-8"/><linearGradient id="notoManStudentLightSkinTone3" x1="13.893" x2="114.721" y1="109.017" y2="109.017" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset=".001" stop-color="#bfbebe"/><stop offset=".3" stop-color="#212121" stop-opacity="0"/><stop offset=".7" stop-color="#212121" stop-opacity="0"/><stop offset="1" stop-color="#bfbebe"/></linearGradient><path fill="url(#notoManStudentLightSkinTone3)" d="M116 12.98c-30.83-7.75-52-8-52-8s-21.17.25-52 8v.77c0 1.33.87 2.5 2.14 2.87c3.72 1.1 13.13 3.69 18.18 4.71c0 0-.96 1.56-1.83 3.53c0 0 8.14 5.55 33.52 8.12c25.38-2.56 33.76-7.58 33.76-7.58c-.88-1.81-1.79-3.49-1.79-3.49c4.5-.74 14.23-4.07 17.95-5.26c1.25-.4 2.09-1.55 2.09-2.86v-.81z" opacity="0.4"/><path fill="#212121" d="M114.5 120.99c0-14.61-21.75-21.54-40.72-23.1l-8.6 11.03c-.28.36-.72.58-1.18.58s-.9-.21-1.18-.58L54.2 97.87c-10.55.81-40.71 4.75-40.71 23.12V124h101z"/><radialGradient id="notoManStudentLightSkinTone4" cx="64" cy="5.397" r="54.167" gradientTransform="matrix(1 0 0 -.5247 0 125.435)" gradientUnits="userSpaceOnUse"><stop offset=".598" stop-color="#212121"/><stop offset="1" stop-color="#616161"/></radialGradient><path fill="url(#notoManStudentLightSkinTone4)" d="M114.5 120.99c0-14.61-21.75-21.54-40.72-23.1l-8.6 11.03c-.28.36-.72.58-1.18.58s-.9-.21-1.18-.58L54.2 97.87c-10.55.81-40.71 4.75-40.71 23.12V124h101z"/></svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2">Giáo viên</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $roleCounts['teacher'] }}</p>
                                </div>
                                <a href="{{ route('admin.account.list', 'teacher')}}">
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 128 128"><linearGradient id="notoTeacherLightSkinTone0" x1="63.999" x2="63.999" y1="116.605" y2="39.511" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#26a69a"/><stop offset="1" stop-color="#00796b"/></linearGradient><path fill="url(#notoTeacherLightSkinTone0)" d="M6.36 10.9h115.29v77.52H6.36z"/><linearGradient id="notoTeacherLightSkinTone1" x1="63.999" x2="63.999" y1="119.455" y2="37.224" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#8d6e63"/><stop offset=".779" stop-color="#795548"/></linearGradient><path fill="url(#notoTeacherLightSkinTone1)" d="M119.29 13.26v72.81H8.71V13.26zM124 8.55H4v82.23h120z"/><path fill="#312d2d" d="M98.9 79.85c-1.25-2.27.34-4.58 3.06-7.44c4.31-4.54 9-15.07 4.64-25.76c.03-.06-.86-1.86-.83-1.92l-1.79-.09c-.57-.08-20.26-.12-39.97-.12s-39.4.04-39.97.12c0 0-2.65 1.95-2.63 2.01c-4.35 10.69.33 21.21 4.64 25.76c2.71 2.86 4.3 5.17 3.06 7.44c-1.21 2.21-4.81 2.53-4.81 2.53s.83 2.26 2.83 3.48c1.85 1.13 4.13 1.39 5.7 1.43c0 0 6.15 8.51 22.23 8.51h17.9c16.08 0 22.23-8.51 22.23-8.51c1.57-.04 3.85-.3 5.7-1.43c2-1.22 2.83-3.48 2.83-3.48s-3.61-.32-4.82-2.53"/><radialGradient id="notoTeacherLightSkinTone2" cx="99.638" cy="45.85" r="23.419" gradientTransform="matrix(1 0 0 .4912 -21.055 59.629)" gradientUnits="userSpaceOnUse"><stop offset=".728" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTone2)" d="M63.99 95.79v-9.44l28.57-2.26l2.6 3.2s-6.15 8.51-22.23 8.51z"/><radialGradient id="notoTeacherLightSkinTone3" cx="76.573" cy="49.332" r="6.921" gradientTransform="matrix(-.9057 .4238 -.3144 -.6719 186.513 79.36)" gradientUnits="userSpaceOnUse"><stop offset=".663" stop-color="#454140"/><stop offset="1" stop-color="#454140" stop-opacity="0"/></radialGradient><path fill="url(#notoTeacherLightSkinTone3)" d="M95.1 83.16c-4.28-6.5 5.21-8.93 5.21-8.93l.01.01c-1.65 2.05-2.4 3.84-1.43 5.61c1.21 2.21 4.81 2.53 4.81 2.53s-4.91 4.36-8.6.78"/><radialGradient id="notoTeacherLightSkinTone4" cx="94.509" cy="68.91" r="30.399" gradientTransform="matrix(-.0746 -.9972 .8311 -.0622 33.494 157.622)" gradientUnits="userSpaceOnUse"><stop offset=".725" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTone4)" d="M106.62 46.65c4.25 10.35-.22 21.01-4.41 25.51c-.57.62-3.01 3.01-3.57 4.92c0 0-9.54-13.31-12.39-21.13c-.57-1.58-1.1-3.2-1.17-4.88c-.05-1.26.14-2.76.87-3.83c.89-1.31 20.16-1.7 20.16-1.7c0 .01.51 1.11.51 1.11"/><radialGradient id="notoTeacherLightSkinTone5" cx="44.31" cy="68.91" r="30.399" gradientTransform="matrix(.0746 -.9972 -.8311 -.0622 98.274 107.563)" gradientUnits="userSpaceOnUse"><stop offset=".725" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTone5)" d="M21.4 46.65c-4.24 10.35.23 21.01 4.41 25.5c.58.62 3.01 3.01 3.57 4.92c0 0 9.54-13.31 12.39-21.13c.58-1.58 1.1-3.2 1.17-4.88c.05-1.26-.14-2.76-.87-3.83c-.89-1.31-1.93-.96-3.44-.96c-2.88 0-15.49-.74-16.47-.74c.01.02-.76 1.12-.76 1.12"/><radialGradient id="notoTeacherLightSkinTone6" cx="49.439" cy="45.85" r="23.419" gradientTransform="matrix(-1 0 0 .4912 98.878 59.629)" gradientUnits="userSpaceOnUse"><stop offset=".728" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTone6)" d="M64.03 95.79v-9.44l-28.57-2.26l-2.6 3.2s6.15 8.51 22.23 8.51z"/><radialGradient id="notoTeacherLightSkinTone7" cx="26.374" cy="49.332" r="6.921" gradientTransform="matrix(.9057 .4238 .3144 -.6719 -13.024 100.635)" gradientUnits="userSpaceOnUse"><stop offset=".663" stop-color="#454140"/><stop offset="1" stop-color="#454140" stop-opacity="0"/></radialGradient><path fill="url(#notoTeacherLightSkinTone7)" d="M32.92 83.16c4.28-6.5-5.21-8.93-5.21-8.93l-.01.01c1.65 2.05 2.4 3.84 1.43 5.61c-1.21 2.21-4.81 2.53-4.81 2.53s4.91 4.36 8.6.78"/><linearGradient id="notoTeacherLightSkinTone8" x1="64" x2="64" y1="25.908" y2="10.938" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#e1f5fe"/><stop offset="1" stop-color="#81d4fa"/></linearGradient><path fill="url(#notoTeacherLightSkinTone8)" d="M114.5 120.75c0-15.47-25.34-23.56-50.36-23.56H64c-25.14.03-50.5 7.32-50.5 23.56V124h101z"/><path fill="#edc391" d="M64 92.33h-9.08v9.98l9.06 2.38l9.1-2.38v-9.98z"/><linearGradient id="notoTeacherLightSkinTone9" x1="29.113" x2="29.113" y1="29.156" y2="4.97" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ffa000"/><stop offset=".341" stop-color="#ff9300"/><stop offset=".972" stop-color="#ff7100"/><stop offset="1" stop-color="#ff6f00"/></linearGradient><path fill="url(#notoTeacherLightSkinTone9)" d="M12 120.75V124h32.89l1.33-27.04C27.52 99.72 12 107.15 12 120.75"/><linearGradient id="notoTeacherLightSkinTonea" x1="98.888" x2="98.888" y1="29.435" y2="4.807" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ffa000"/><stop offset=".341" stop-color="#ff9300"/><stop offset=".972" stop-color="#ff7100"/><stop offset="1" stop-color="#ff6f00"/></linearGradient><path fill="url(#notoTeacherLightSkinTonea)" d="M81.78 96.96L83.1 124H116v-3.25c0-13.6-15.52-21.03-34.22-23.79"/><path fill="#66c0e8" d="m54.03 92.12l9.99 12.82l-16.24 6.64l-2.41-14.54z"/><path fill="#66c0e8" d="m73.97 92.12l-9.99 12.82l16.24 6.64l2.41-14.54z"/><path fill="#af5214" d="M48.88 95s-1.14 2.72-1.94 6c-1.59 6.52-1.69 15.8-1.69 15.8s-6.89-2.34-9.04-8.05c-2.54-6.75 1.75-10.46 1.75-10.46s.9-.38 4.68-2.46s6.24-.83 6.24-.83m30.24 0s1.14 2.72 1.94 6c1.59 6.52 1.69 15.8 1.69 15.8s6.89-2.34 9.04-8.05c2.54-6.75-1.75-10.46-1.75-10.46s-.9-.38-4.68-2.46s-6.24-.83-6.24-.83"/><path fill="#edc391" d="M91.12 50.43H36.47c-5.89 0-10.71 5.14-10.71 11.41s4.82 11.41 10.71 11.41h54.65c5.89 0 10.71-5.14 10.71-11.41s-4.82-11.41-10.71-11.41"/><path fill="#f9ddbd" d="M63.79 11.07c-17.4 0-33.52 18.61-33.52 45.4c0 26.64 16.61 39.81 33.52 39.81S97.31 83.1 97.31 56.46c0-26.78-16.11-45.39-33.52-45.39"/><g fill="#312d2d"><ellipse cx="47.98" cy="58.81" rx="4.93" ry="5.1"/><ellipse cx="79.13" cy="58.81" rx="4.93" ry="5.1"/></g><path fill="#454140" d="M55.37 49.82c-.93-1.23-3.07-3.01-7.23-3.01s-6.31 1.79-7.23 3.01c-.41.54-.31 1.17-.02 1.55c.26.35 1.04.68 1.9.39s2.54-1.16 5.35-1.18c2.81.02 4.49.89 5.35 1.18s1.64-.03 1.9-.39c.28-.38.39-1.01-.02-1.55m30.99 0c-.93-1.23-3.07-3.01-7.23-3.01s-6.31 1.79-7.23 3.01c-.41.54-.31 1.17-.02 1.55c.26.35 1.04.68 1.9.39s2.54-1.16 5.35-1.18c2.81.02 4.49.89 5.35 1.18s1.64-.03 1.9-.39c.29-.38.39-1.01-.02-1.55"/><path fill="#dba689" d="M67.65 68.06c-.11-.04-.21-.07-.32-.08h-7.08c-.11.01-.22.04-.32.08c-.64.26-.99.92-.69 1.63s1.71 2.69 4.55 2.69s4.25-1.99 4.55-2.69c.31-.71-.05-1.37-.69-1.63"/><path fill="#444" d="M72.32 76.14c-3.18 1.89-13.63 1.89-16.81 0c-1.83-1.09-3.7.58-2.94 2.24c.75 1.63 6.44 5.42 11.37 5.42s10.55-3.79 11.3-5.42c.76-1.66-1.09-3.33-2.92-2.24"/><path fill="#212121" stroke="#212121" stroke-miterlimit="10" stroke-width="0.55" d="M93.83 52.93c-.07-1.19-.12-1.31-1.69-1.81c-1.23-.39-7.95-.94-13.01-.66c-.36.02-.71.04-1.04.07c-4.59.39-10.1 2.24-14.24 2.34c-1.76.04-9.01-1.86-14.14-2.26c-.33-.02-.66-.05-1-.06c-5.07-.26-11.82.33-13.05.73c-1.57.51-1.62.63-1.68 1.82c-.07 1.19.13 2.2 1.06 2.51c1.27.42 1.28 2 2.13 6.54c.77 4.14 2.62 7.41 10.57 7.98c.34.02.66.04.98.04c7.03.1 9.45-4.53 10.25-6.07c1.49-2.86 1.02-6.8 4.96-6.81c3.93-.01 3.56 3.86 5.07 6.71c.81 1.53 3.17 6.18 10.14 6.08c.34 0 .69-.02 1.05-.05c7.94-.62 9.78-3.9 10.52-8.04c.82-4.55.83-6.14 2.09-6.56c.91-.3 1.11-1.31 1.03-2.5zM53.28 68.17c-1.22.57-2.85.86-4.57.86c-3.59-.01-7.57-1.27-9.01-3.81c-2.04-3.62-2.57-10.94.03-12.47c1.14-.67 4.99-1.13 8.97-.96c4.13.18 8.4 1.04 9.94 3.06c2.55 3.33-1.5 11.5-5.36 13.32zm34.9-3.1c-1.43 2.56-5.44 3.85-9.05 3.86c-1.7.01-3.31-.27-4.51-.83c-3.87-1.8-7.97-9.94-5.45-13.29c1.53-2.04 5.82-2.92 9.96-3.12c3.97-.19 7.81.25 8.94.91c2.61 1.52 2.13 8.84.11 12.47z"/><linearGradient id="notoTeacherLightSkinToneb" x1="79.569" x2="76.946" y1="22.713" y2="11.668" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset=".002" stop-color="#212121" stop-opacity="0.2"/><stop offset="1" stop-color="#212121" stop-opacity="0.6"/></linearGradient><path fill="url(#notoTeacherLightSkinToneb)" d="m101.67 121.61l.57-2.2l.01-.05l1.93-7.6l-6.9-1.98l-34.92-10.03c-.05-.01-.09-.01-.13-.03a6.177 6.177 0 0 0-7.51 4.27L48.97 124h52.02z" opacity="0.67"/><path fill="#424242" d="M105.75 111.88c.29-1.01-.29-2.06-1.3-2.34l-38.69-11.1a6.19 6.19 0 0 0-7.65 4.24L52 124h50.28z"/><linearGradient id="notoTeacherLightSkinTonec" x1="81.84" x2="79.869" y1="17.098" y2="10.486" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ef5350"/><stop offset="1" stop-color="#e53935"/></linearGradient><path fill="url(#notoTeacherLightSkinTonec)" d="M105.08 120.31c.35-1.22-.38-2.5-1.62-2.85l-41.52-11.9c-4.53-1.3-5.32 2.35-6.59 6.78L52 124h52.02z"/><linearGradient id="notoTeacherLightSkinToned" x1="58.405" x2="60.268" y1="19.113" y2="24.969" gradientTransform="matrix(1 0 0 -1 0 128)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#212121"/><stop offset="1" stop-color="#424242"/></linearGradient><path fill="url(#notoTeacherLightSkinToned)" d="M63.26 98.24a6.17 6.17 0 0 0-5.14 4.42L52 124h3.87z"/><path fill="#424242" d="M64.33 101.57c.18 0 .38.02.59.07l37.25 10.7l-.31 1.08c-11.79-3.29-34.29-9.62-38.94-11.16c.24-.33.71-.69 1.41-.69m0-3.33c-4.52 0-6.78 5.57-3.12 6.94c4.03 1.5 42.93 12.32 42.93 12.32l1.58-5.52c.31-1.06-.19-2.14-1.11-2.4L65.77 98.42q-.75-.18-1.44-.18" opacity="0.2"/><linearGradient id="notoTeacherLightSkinTonee" x1="-117.44" x2="-73.995" y1="-972.312" y2="-972.312" gradientTransform="matrix(.9612 .2758 -.3192 1.1123 -136.555 1216.41)" gradientUnits="userSpaceOnUse"><stop offset=".01" stop-color="#bdbdbd"/><stop offset=".987" stop-color="#f8f8f7"/></linearGradient><path fill="url(#notoTeacherLightSkinTonee)" d="m103.37 112.12l-39.8-11.42c-1.08-.31-2.26.46-2.62 1.71l-.06.22c-.36 1.25.23 2.53 1.31 2.84l39.8 11.42s-.34-.83.07-2.3c.41-1.48 1.3-2.47 1.3-2.47"/><path fill="#312d2d" d="M104.07 25.11c-2.44-3.69-7.91-8.64-12.82-8.97c-.79-4.72-5.84-8.72-10.73-10.27c-13.23-4.19-21.84.51-26.46 3.03c-.96.52-7.17 3.97-11.51 1.5c-2.72-1.55-2.67-5.74-2.67-5.74s-8.52 3.25-5.61 12.3c-2.93.12-6.77 1.36-8.8 5.47c-2.42 4.9-1.56 8.99-.86 10.95c-2.52 2.14-5.69 6.69-3.52 12.6c1.64 4.45 8.17 6.5 8.17 6.5c-.46 8.01 1.03 12.94 1.82 14.93c.14.35.63.32.72-.04c.99-3.97 4.37-17.8 4.03-20.21c0 0 11.35-2.25 22.17-10.22c2.2-1.62 4.59-3 7.13-4.01c13.59-5.41 16.43 3.82 16.43 3.82s9.42-1.81 12.26 11.27c1.07 4.9 1.79 12.75 2.4 18.24c.04.39.57.47.72.11c.95-2.18 2.85-6.5 3.3-10.91c.16-1.55 4.34-3.6 6.14-10.26c2.41-8.88-.54-17.42-2.31-20.09"/><radialGradient id="notoTeacherLightSkinTonef" cx="82.019" cy="84.946" r="35.633" gradientTransform="matrix(.3076 .9515 .706 -.2282 -3.184 -15.605)" gradientUnits="userSpaceOnUse"><stop offset=".699" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTonef)" d="M100.22 55.5c.16-1.55 4.34-3.6 6.14-10.26c.19-.71.35-1.43.5-2.15c1.46-8.09-1.16-15.52-2.79-17.98c-2.26-3.41-7.1-7.89-11.69-8.81c-.4-.05-.79-.1-1.16-.12c0 0 .33 2.15-.54 3.86c-1.12 2.22-3.41 2.75-3.41 2.75c11.97 11.98 11.12 22 12.95 32.71"/><radialGradient id="notoTeacherLightSkinToneg" cx="47.28" cy="123.8" r="9.343" gradientTransform="matrix(.8813 .4726 .5603 -1.045 -63.752 111.228)" gradientUnits="userSpaceOnUse"><stop offset=".58" stop-color="#454140"/><stop offset="1" stop-color="#454140" stop-opacity="0"/></radialGradient><path fill="url(#notoTeacherLightSkinToneg)" d="M56.95 7.39c-1.1.53-2.06 1.06-2.9 1.51c-.96.52-7.17 3.97-11.51 1.5c-2.67-1.52-2.67-5.58-2.67-5.72c-1.23 1.57-4.95 12.78 5.93 13.53c4.69.32 7.58-3.77 9.3-7.23c.62-1.26 1.59-3.1 1.85-3.59"/><radialGradient id="notoTeacherLightSkinToneh" cx="159.055" cy="62.862" r="28.721" gradientTransform="matrix(-.9378 -.3944 -.2182 .5285 231.04 50.678)" gradientUnits="userSpaceOnUse"><stop offset=".699" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinToneh)" d="M79.16 5.47c7.32 1.98 10.89 5.71 12.08 10.68c.35 1.46.77 15.08-25.23-.4c-9.67-5.76-7.03-9.36-5.9-9.77c4.42-1.6 10.85-2.73 19.05-.51"/><radialGradient id="notoTeacherLightSkinTonei" cx="43.529" cy="115.276" r="8.575" gradientTransform="matrix(1 0 0 -1.2233 0 153.742)" gradientUnits="userSpaceOnUse"><stop offset=".702" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTonei)" d="M39.84 4.68c-.01.01-.03.01-.06.03h-.01c-.93.39-8.24 3.78-5.51 12.25l7.78 1.25c-6.89-6.98-2.17-13.55-2.17-13.55s-.02.01-.03.02"/><radialGradient id="notoTeacherLightSkinTonej" cx="42.349" cy="100.139" r="16.083" gradientTransform="matrix(-.9657 -.2598 -.2432 .9037 107.598 -51.632)" gradientUnits="userSpaceOnUse"><stop offset=".66" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTonej)" d="m39.07 17.73l-4.81-.77c-.19 0-.83.06-1.18.11c-2.71.38-5.9 1.78-7.63 5.36c-1.86 3.86-1.81 7.17-1.3 9.38c.15.74.45 1.58.45 1.58s2.38-2.26 8.05-2.41z"/><radialGradient id="notoTeacherLightSkinTonek" cx="38.533" cy="84.609" r="16.886" gradientTransform="matrix(.9907 .1363 .1915 -1.3921 -15.841 155.923)" gradientUnits="userSpaceOnUse"><stop offset=".598" stop-color="#454140" stop-opacity="0"/><stop offset="1" stop-color="#454140"/></radialGradient><path fill="url(#notoTeacherLightSkinTonek)" d="M24.37 33.58c-2.37 2.1-5.56 6.79-3.21 12.61c1.77 4.39 8.09 6.29 8.09 6.29c0 .02 1.26.4 1.91.4l1.48-21.9c-3.03 0-5.94.91-7.82 2.22c.03.03-.46.35-.45.38"/></svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2">Admin</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $roleCounts['admin'] }}</p>
                                </div>
                                <a href="{{ route('admin.account.list', 'admin')}}">
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 36 36"><circle cx="14.67" cy="8.3" r="6" fill="#0d0d0d" class="clr-i-solid clr-i-solid-path-1"/><path fill="#0d0d0d" d="M16.44 31.82a2.15 2.15 0 0 1-.38-2.55l.53-1l-1.09-.33a2.14 2.14 0 0 1-1.5-2.1v-2.05a2.16 2.16 0 0 1 1.53-2.07l1.09-.33l-.52-1a2.17 2.17 0 0 1 .35-2.52a19 19 0 0 0-2.32-.16A15.58 15.58 0 0 0 2 23.07v7.75a1 1 0 0 0 1 1z" class="clr-i-solid clr-i-solid-path-2"/><path fill="#0d0d0d" d="m33.7 23.46l-2-.6a6.7 6.7 0 0 0-.58-1.42l1-1.86a.35.35 0 0 0-.07-.43l-1.45-1.46a.38.38 0 0 0-.43-.07l-1.85 1a7.7 7.7 0 0 0-1.43-.6l-.61-2a.38.38 0 0 0-.36-.25h-2.08a.38.38 0 0 0-.35.26l-.6 2a7 7 0 0 0-1.45.61l-1.81-1a.38.38 0 0 0-.44.06l-1.47 1.44a.37.37 0 0 0-.07.44l1 1.82a7.2 7.2 0 0 0-.65 1.43l-2 .61a.36.36 0 0 0-.26.35v2.05a.36.36 0 0 0 .26.35l2 .61a7.3 7.3 0 0 0 .6 1.41l-1 1.9a.37.37 0 0 0 .07.44L19.16 32a.38.38 0 0 0 .44.06l1.87-1a7 7 0 0 0 1.4.57l.6 2.05a.38.38 0 0 0 .36.26h2.05a.38.38 0 0 0 .35-.26l.6-2.05a6.7 6.7 0 0 0 1.38-.57l1.89 1a.38.38 0 0 0 .44-.06L32 30.55a.38.38 0 0 0 .06-.44l-1-1.88a7 7 0 0 0 .57-1.38l2-.61a.39.39 0 0 0 .27-.35v-2.07a.4.4 0 0 0-.2-.36m-8.83 4.72a3.34 3.34 0 1 1 3.33-3.34a3.34 3.34 0 0 1-3.33 3.34" class="clr-i-solid clr-i-solid-path-3"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2">Nhân viên</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $roleCounts['staff'] }}</p>
                                </div>
                                <a href="{{ route('admin.account.list', 'staff')}}">
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" viewBox="0 0 24 24"><path fill="#0d0d0d" d="M14 20v-1.25q0-.4.163-.763t.437-.637l4.925-4.925q.225-.225.5-.325t.55-.1q.3 0 .575.113t.5.337l.925.925q.2.225.313.5t.112.55t-.1.563t-.325.512l-4.925 4.925q-.275.275-.637.425t-.763.15H15q-.425 0-.712-.288T14 20M4 19v-1.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13q.925 0 1.825.113t1.8.362l-2.75 2.75q-.425.425-.65.975T12 18.35V20H5q-.425 0-.712-.288T4 19m16.575-3.6l.925-.975l-.925-.925l-.95.95zM12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 4t2.825 1.175T16 8t-1.175 2.825T12 12"/></svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách tất cả người dùng</h4>
                    </div> <!-- end card-header-->
                    <div class="card-body p-0">
                        <div class="px-3" data-simplebar style="max-height: 398px;">
                            <table class="table table-hover mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Giới tính</th>
                                        <th>Ngày sinh nhật</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($role as $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->gender }}</td>
                                            <td>{{ $data->birth_date }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->phone }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            {!! $role->links('pagination::bootstrap-5') !!}
                        </nav>
                    </div>
                </div>

            </div>

            
        </div>
        <!-- end row -->
        <!-- End Container Fluid -->
        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
    </div>
    </footer>

@endsection
