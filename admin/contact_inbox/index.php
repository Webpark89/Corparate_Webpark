<?php
/**
 * Admin contact inbox — list, filter, inspect, and update customer contact submissions.
 */
$pageTitle = 'ข้อความจากลูกค้า';
$page = 'contact_inbox';
require_once __DIR__ . '/../includes/header.php';

$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 15;

// Status counts
$countAll = (int) db()->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$countNew = (int) db()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
$countRead = (int) db()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'read'")->fetchColumn();
$countReplied = (int) db()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'replied'")->fetchColumn();
$countArchived = (int) db()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'archived'")->fetchColumn();

// Query construction
$sql = 'SELECT * FROM contact_messages WHERE 1=1';
$countSql = 'SELECT COUNT(*) FROM contact_messages WHERE 1=1';
$params = [];

if ($statusFilter !== '' && in_array($statusFilter, ['new', 'read', 'replied', 'archived'], true)) {
    $sql .= ' AND status = ?';
    $countSql .= ' AND status = ?';
    $params[] = $statusFilter;
}

if ($search !== '') {
    $searchCond = ' AND (first_name LIKE ? OR last_name LIKE ? OR company_name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)';
    $sql .= $searchCond;
    $countSql .= $searchCond;
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

// Count total
$countStmt = db()->prepare($countSql);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

// Get paginated rows
$sql .= ' ORDER BY created_at DESC LIMIT ' . (int)$pagination['perPage'] . ' OFFSET ' . (int)$pagination['offset'];
$stmt = db()->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

$flashSuccess = flash('success');
$flashError = flash('error');
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <!-- Header -->
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-600 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-slate-900">ข้อความจากลูกค้า (Contact Inbox)</h2>
                <?php if ($countNew > 0): ?>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700 animate-pulse">
                        <?= $countNew ?> ข้อความใหม่
                    </span>
                <?php endif; ?>
            </div>
            <p class="mt-1 text-xs text-slate-500">รายการข้อมูลและข้อความที่ผู้ใช้ส่งผ่านหน้าติดต่อเราทั้งหมด</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">ทั้งหมด <?= number_format($totalRows) ?> รายการ</span>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if ($flashSuccess): ?>
        <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= e($flashSuccess) ?>
        </div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?= e($flashError) ?>
        </div>
    <?php endif; ?>

    <!-- Status Tabs & Filter (Matching Reference Design) -->
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4 md:px-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold text-slate-500 mr-1 select-none">ตัวกรอง:</span>
            
            <!-- All -->
            <a href="?status=&search=<?= urlencode($search) ?>"
                class="inline-flex items-center gap-2 rounded-full px-6 py-2.5 text-xs transition-all <?= $statusFilter === '' ? 'bg-slate-900 text-white font-semibold shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                <span>ทั้งหมด (<?= $countAll ?>)</span>
            </a>

            <!-- New (Blue) -->
            <a href="?status=new&search=<?= urlencode($search) ?>"
                class="inline-flex items-center gap-2.5 rounded-full px-6 py-2.5 text-xs transition-all <?= $statusFilter === 'new' ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                <span class="text-xs">📨</span>
                <span>ข้อความใหม่</span>
                <span class="inline-flex items-center justify-center min-w-[20px] px-2 py-0.5 rounded-full text-[11px] font-bold <?= $statusFilter === 'new' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-700' ?>">
                    <?= $countNew ?>
                </span>
            </a>

            <!-- Read (Orange / Amber) -->
            <a href="?status=read&search=<?= urlencode($search) ?>"
                class="inline-flex items-center gap-2.5 rounded-full px-6 py-2.5 text-xs transition-all <?= $statusFilter === 'read' ? 'bg-amber-500 text-white font-semibold shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                <span class="text-xs">👀</span>
                <span>อ่านแล้ว</span>
                <span class="inline-flex items-center justify-center min-w-[20px] px-2 py-0.5 rounded-full text-[11px] font-bold <?= $statusFilter === 'read' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' ?>">
                    <?= $countRead ?>
                </span>
            </a>

            <!-- Replied (Green / Emerald) -->
            <a href="?status=replied&search=<?= urlencode($search) ?>"
                class="inline-flex items-center gap-2.5 rounded-full px-6 py-2.5 text-xs transition-all <?= $statusFilter === 'replied' ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                <span class="text-xs">💬</span>
                <span>ตอบแล้ว</span>
                <span class="inline-flex items-center justify-center min-w-[20px] px-2 py-0.5 rounded-full text-[11px] font-bold <?= $statusFilter === 'replied' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700' ?>">
                    <?= $countReplied ?>
                </span>
            </a>

            <!-- Archived (Red) -->
            <a href="?status=archived&search=<?= urlencode($search) ?>"
                class="inline-flex items-center gap-2.5 rounded-full px-6 py-2.5 text-xs transition-all <?= $statusFilter === 'archived' ? 'bg-red-600 text-white font-semibold shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 font-medium' ?>">
                <span class="text-xs">📁</span>
                <span>เก็บถาวร</span>
                <span class="inline-flex items-center justify-center min-w-[20px] px-2 py-0.5 rounded-full text-[11px] font-bold <?= $statusFilter === 'archived' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-700' ?>">
                    <?= $countArchived ?>
                </span>
            </a>
        </div>

        <!-- Search Form (Spacious Pill Input with Icon) -->
        <form method="get" class="relative flex items-center w-full md:w-auto flex-1">
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" placeholder="ค้นหาชื่อ, อีเมล, เบอร์โทร, บริษัท..." value="<?= e($search) ?>"
                class="w-full rounded-full border border-slate-200 bg-white py-2.5 px-11 text-xs text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/10 transition shadow-2xs">
            <?php if ($search !== ''): ?>
                <a href="?status=<?= urlencode($statusFilter) ?>" class="absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-slate-400 hover:text-slate-600" title="ล้างการค้นหา">✕</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table Container -->
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50/80">
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 select-none">
                        <th class="px-6 py-4 text-left whitespace-nowrap">วันที่ & เวลา</th>
                        <th class="px-6 py-4 text-left whitespace-nowrap">ผู้ติดต่อ (NAME)</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">บริษัท (COMPANY)</th>
                        <th class="px-4 py-4 text-left whitespace-nowrap">ข้อมูลติดต่อ (CONTACT)</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">สถานะ (STATUS)</th>
                        <th class="px-4 py-4 text-center whitespace-nowrap">ส่งเมล</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-xs text-slate-400 border-dashed">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>ไม่พบข้อความติดต่อตามเงื่อนไขที่เลือก</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $row): 
                            $isNew = ($row['status'] === 'new');
                            $status = $row['status'];
                            $badgeClass = match ($status) {
                                'new'      => 'bg-blue-50 text-blue-700 border-blue-200 font-bold',
                                'read'     => 'bg-slate-100 text-slate-600 border-slate-200',
                                'replied'  => 'bg-emerald-50 text-emerald-700 border-emerald-200 font-semibold',
                                'archived' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default    => 'bg-slate-100 text-slate-600'
                            };
                        ?>
                            <tr class="hover:bg-slate-50/60 transition-colors <?= $isNew ? 'bg-blue-50/20' : '' ?>">
                                <!-- Date & Time -->
                                <td class="px-6 py-5 whitespace-nowrap text-slate-500">
                                    <div class="font-semibold text-slate-800 text-xs"><?= date('d/m/Y', strtotime($row['created_at'])) ?></div>
                                    <div class="text-[11px] text-slate-400 mt-1"><?= date('H:i', strtotime($row['created_at'])) ?> น.</div>
                                </td>

                                <!-- Full Name & Message Preview (Aligned perfectly in a clean column) -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="font-semibold text-slate-900 text-sm leading-snug">
                                            <?= e($row['first_name'] . ' ' . $row['last_name']) ?>
                                        </span>
                                        <span class="text-[11px] text-slate-400 truncate max-w-[240px] leading-relaxed" title="<?= e($row['message']) ?>">
                                            <?= e(mb_strimwidth($row['message'], 0, 45, '...')) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Company Name -->
                                <td class="px-4 py-5 text-slate-600">
                                    <span class="inline-block max-w-[150px] truncate text-xs">
                                        <?= e(!empty($row['company_name']) ? $row['company_name'] : '-') ?>
                                    </span>
                                </td>

                                <!-- Phone & Email -->
                                <td class="px-4 py-5">
                                    <div class="text-slate-800 font-mono text-xs">
                                        <a href="tel:<?= e($row['phone']) ?>" class="hover:text-blue-600 transition"><?= e($row['phone']) ?></a>
                                    </div>
                                    <div class="text-slate-500 text-[11px] truncate max-w-[180px] mt-1">
                                        <a href="mailto:<?= e($row['email']) ?>" class="hover:text-blue-600 transition"><?= e($row['email']) ?></a>
                                    </div>
                                </td>

                                <!-- Status Badge & Quick Change -->
                                <td class="px-4 py-5 text-center whitespace-nowrap">
                                    <form method="post" action="update_status.php" class="inline-block">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                        <input type="hidden" name="return_url" value="<?= e($_SERVER['REQUEST_URI'] ?? '') ?>">
                                        <select name="status" onchange="this.form.submit()"
                                             class="text-[11px] font-semibold rounded-lg px-3 py-1.5 border cursor-pointer transition focus:outline-none focus:ring-2 focus:ring-blue-500/20 <?= $badgeClass ?>">
                                            <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>🔵 ใหม่</option>
                                            <option value="read" <?= $status === 'read' ? 'selected' : '' ?>>⚪ อ่านแล้ว</option>
                                            <option value="replied" <?= $status === 'replied' ? 'selected' : '' ?>>🟢 ตอบกลับแล้ว</option>
                                            <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>🟠 เก็บถาวร</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- Email Sent -->
                                <td class="px-4 py-5 text-center whitespace-nowrap">
                                    <?php if (!empty($row['email_sent'])): ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-600" title="ส่งอีเมลแจ้งเตือนสำเร็จ">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            ส่งแล้ว
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] text-slate-400" title="ไม่ได้ส่งอีเมล หรือส่งไม่สำเร็จ">
                                            -
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Actions (Icons Only with Hover Colors & Tooltips) -->
                                <td class="px-6 py-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View Detail Icon Button (Eye Icon) -->
                                        <button type="button" onclick="openDetailModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)"
                                            class="w-8 h-8 rounded-xl flex items-center justify-center border border-slate-200 bg-white text-slate-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 shadow-2xs transition-all duration-200 cursor-pointer"
                                            title="ดูรายละเอียดข้อความ">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Icon Button (Trash Icon) -->
                                        <form method="post" action="delete.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อความนี้?')" class="inline-block">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                            <input type="hidden" name="return_url" value="<?= e($_SERVER['REQUEST_URI'] ?? '') ?>">
                                            <button type="submit"
                                                class="w-8 h-8 rounded-xl flex items-center justify-center border border-slate-200 bg-white text-slate-400 hover:bg-red-50 hover:text-red-600 hover:border-red-300 shadow-2xs transition-all duration-200 cursor-pointer"
                                                title="ลบข้อความ">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['pages'] > 1): ?>
            <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-4 py-3 text-xs text-slate-500">
                <div>
                    แสดง <?= ($pagination['offset'] + 1) ?> - <?= min($pagination['total'], $pagination['offset'] + $pagination['perPage']) ?> จากทั้งหมด <?= $pagination['total'] ?> รายการ
                </div>
                <div class="flex items-center gap-1">
                    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                        <a href="?p=<?= $i ?>&status=<?= urlencode($statusFilter) ?>&search=<?= urlencode($search) ?>"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border font-semibold transition <?= $i === $pagination['current'] ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- Detail Modal / Sidebar Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div id="detailModalContent" class="w-full max-w-2xl transform rounded-3xl bg-white shadow-2xl border border-slate-100 overflow-hidden transition-all duration-300 scale-95">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">รายละเอียดข้อความติดต่อ</h3>
                    <p class="text-xs text-slate-400" id="modalDateDisplay">-</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
            <!-- Customer Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-100 bg-slate-50/40 p-4">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">ผู้ติดต่อ</div>
                    <div id="modalName" class="mt-1 text-base font-bold text-slate-900">-</div>
                    <div class="mt-2 text-xs text-slate-500">
                        <span class="font-semibold text-slate-400">บริษัท:</span>
                        <span id="modalCompany" class="text-slate-800 font-medium">-</span>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50/40 p-4 space-y-2">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">เบอร์โทรศัพท์</div>
                        <a id="modalPhoneLink" href="#" class="mt-0.5 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span id="modalPhone">-</span>
                        </a>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">อีเมล</div>
                        <a id="modalEmailLink" href="#" class="mt-0.5 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline truncate max-w-full">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span id="modalEmail">-</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Message Box -->
            <div>
                <div class="text-xs font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    ข้อความจากลูกค้า:
                </div>
                <div id="modalMessage" class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 text-sm text-slate-800 leading-relaxed whitespace-pre-wrap font-sans">
                    -
                </div>
            </div>

            <!-- Meta Information Table -->
            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 text-xs text-slate-500 space-y-2">
                <div class="font-bold text-slate-700 border-b border-slate-200 pb-1.5">ข้อมูลความปลอดภัยและ PDPA</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                    <div>
                        <span class="text-slate-400">PDPA Consent:</span>
                        <span id="modalPdpa" class="font-semibold text-emerald-600">ยินยอม</span>
                    </div>
                    <div>
                        <span class="text-slate-400">เวลา Consent:</span>
                        <span id="modalPdpaAt" class="font-mono text-slate-700">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400">IP Address:</span>
                        <span id="modalIp" class="font-mono text-slate-700">-</span>
                    </div>
                    <div>
                        <span class="text-slate-400">อีเมลแจ้งเตือน:</span>
                        <span id="modalEmailSent" class="font-semibold">-</span>
                    </div>
                    <div class="sm:col-span-2 truncate">
                        <span class="text-slate-400">หน้าที่ส่งฟอร์ม:</span>
                        <span id="modalSourcePage" class="font-mono text-slate-700">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer / Quick Status Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
            <div class="flex items-center gap-1.5 w-full sm:w-auto">
                <span class="text-xs font-semibold text-slate-500">เปลี่ยนสถานะ:</span>
                <form id="modalStatusForm" method="post" action="update_status.php" class="inline-flex gap-1.5">
                    <?= csrf_field() ?>
                    <input type="hidden" id="modalFormId" name="id" value="">
                    <input type="hidden" name="return_url" value="<?= e($_SERVER['REQUEST_URI'] ?? '') ?>">
                    <button type="submit" name="status" value="read" class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        ทำเครื่องหมายว่าอ่านแล้ว
                    </button>
                    <button type="submit" name="status" value="replied" class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                        ทำเครื่องหมายว่าตอบแล้ว
                    </button>
                    <button type="submit" name="status" value="archived" class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition">
                        เก็บถาวร
                    </button>
                </form>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-full sm:w-auto rounded-xl bg-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-300 transition">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>

<script>
    const detailModal = document.getElementById('detailModal');
    const detailModalContent = document.getElementById('detailModalContent');

    function openDetailModal(data) {
        if (!data) return;

        // Auto mark as read if status is 'new'
        if (data.status === 'new') {
            const formData = new FormData();
            formData.append('_csrf', '<?= csrf_token() ?>');
            formData.append('id', data.id);
            formData.append('status', 'read');
            formData.append('ajax', '1');
            fetch('update_status.php', { method: 'POST', body: formData }).catch(() => {});
        }

        document.getElementById('modalFormId').value = data.id || '';
        document.getElementById('modalDateDisplay').textContent = (data.created_at || '') + ' น.';
        document.getElementById('modalName').textContent = (data.first_name || '') + ' ' + (data.last_name || '');
        document.getElementById('modalCompany').textContent = data.company_name || '-';
        document.getElementById('modalPhone').textContent = data.phone || '-';
        document.getElementById('modalPhoneLink').href = 'tel:' + (data.phone || '');
        document.getElementById('modalEmail').textContent = data.email || '-';
        document.getElementById('modalEmailLink').href = 'mailto:' + (data.email || '');
        document.getElementById('modalMessage').textContent = data.message || '-';
        document.getElementById('modalPdpa').textContent = data.pdpa_consent == 1 ? '✓ ยินยอม (Agreed)' : '✕ ไม่ระบุ';
        document.getElementById('modalPdpaAt').textContent = data.pdpa_consent_at || '-';
        document.getElementById('modalIp').textContent = data.ip_address || '-';
        document.getElementById('modalSourcePage').textContent = data.source_page || '-';
        document.getElementById('modalEmailSent').textContent = data.email_sent == 1 ? '✓ ส่งแล้ว' : 'ไม่ได้ส่ง';
        document.getElementById('modalEmailSent').className = data.email_sent == 1 ? 'font-semibold text-emerald-600' : 'font-semibold text-slate-400';

        detailModal.classList.remove('hidden');
        setTimeout(() => {
            detailModal.classList.remove('opacity-0');
            detailModalContent.classList.remove('scale-95');
            detailModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeDetailModal() {
        detailModal.classList.add('opacity-0');
        detailModalContent.classList.remove('scale-100');
        detailModalContent.classList.add('scale-95');
        setTimeout(() => {
            detailModal.classList.add('hidden');
        }, 200);
    }

    // Close on click outside
    detailModal.addEventListener('click', function(e) {
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !detailModal.classList.contains('hidden')) {
            closeDetailModal();
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
