<ul class="menu-inner py-1">
    <!-- Dashboards -->
    <?php $request = service('request'); ?>
    <li class="menu-item <?= ($request->uri->getSegment(2) === 'dashboard') ? 'active' : '' ?>">

        <a href="/admin/dashboard" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Dashboards">Dashboard</div>
        </a>

    </li>
    <li class="menu-item <?= ($request->uri->getSegment(2) === 'profile') ? 'active' : '' ?>">
        <a href="/admin/profile" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="pps">Profile</div>
        </a>
    </li>

    <li class="menu-item <?= ($request->uri->getSegment(2) === 'cmshbaru') ? 'active  ' : '' ?>">
        <a href="/admin/cmshbaru" class="menu-link">
            <i class="menu-icon tf-icons bx bxs-file"></i>
            <div data-i18n="pps">Cmshbaru</div>
        </a>
    </li>
    <li class="menu-item <?= (
                                $request->uri->getSegment(3) === 'jurusan' ||
                                $request->uri->getSegment(3) === 'prodi' ||
                                $request->uri->getSegment(3) === 'tahun'
                            ) ? 'active open' : '' ?>">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-data"></i>
            <div data-i18n="Dashboards">Kode</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item <?= ($request->uri->getSegment(3) === 'jurusan') ? 'active' : '' ?>">
                <a href="/admin/kode/jurusan" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-time-five"></i>
                    <div data-i18n="CRM">Kode Jurusan</div>
                </a>
            </li>
            <li class="menu-item <?= ($request->uri->getSegment(3) === 'prodi') ? 'active' : '' ?>">
                <a href="/admin/kode/prodi" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-briefcase"></i>
                    <div data-i18n="Academy">Kode Prodi</div>
                </a>
            </li>
            <li class="menu-item <?= ($request->uri->getSegment(3) === 'tahun') ? 'active' : '' ?>">
                <a href="/admin/kode/tahun" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Academy">Kode Tahun</div>
                </a>
            </li>
        </ul>
    </li>
   <li class="menu-item <?= ($request->uri->getSegment(2) === 'npm') ? 'active  ' : '' ?>">
        <a href="/admin/npm" class="menu-link">
            <i class="menu-icon tf-icons bx bxs-file"></i>
            <div data-i18n="pps">NPM</div>
        </a>
    </li>
</ul>