# TODO_sitemap_before_after

## Before (collapsed)
- wrapper: bg-slate-950
- SITEMAP: text-white
- arrow: border-white + svg text-white
- panel content: โทนเทา/ขาวตาม class เดิม

## Before (expanded)
- wrapper: ยังเป็น bg-slate-950
- SITEMAP/arrow: ยังไม่สลับสีตาม state ที่ต้องการ
- panel content: ส่วนใหญ่ยังเป็นโทนเท่า/ขาวไม่ครบตาม Figma

---

## After (collapsed)
- wrapper `#footerSitemapWrapper`: bg-white
- SITEMAP title `#footerSitemapTitle`: text-primary
- arrow: border-primary และ svg text-primary
- panel bg: bg-transparent (height=0)

## After (expanded)
- wrapper `#footerSitemapWrapper`: bg-slate-900
- SITEMAP title `#footerSitemapTitle`: text-white
- arrow: border-white และ svg text-white
- panel bg: bg-slate-950
- content ใน `#footerSitemapPanel`:
  - sub-headers: text-white + font-bold
  - list items: text-white/80

> NOTE: ห้าม commit/push/แตะ DB ตามข้อกำหนด

