(function(){
  const sync = () => document.querySelectorAll('.cdb-vis-pair').forEach(pair=>{
    const chart = pair.querySelector('.cdb-radar-emp'); if (!chart) return;
    const w = Math.round(chart.getBoundingClientRect().width);
    if (w>0) pair.style.setProperty('--vis-size', w + 'px');
  });
  const ro = new ResizeObserver(sync);
  document.addEventListener('DOMContentLoaded', ()=>{ document.querySelectorAll('.cdb-vis-pair .cdb-radar-emp').forEach(el=>ro.observe(el)); sync(); });
  window.addEventListener('load', sync);
})();
