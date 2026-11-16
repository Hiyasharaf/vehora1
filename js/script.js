const fallbackSvg = "data:image/svg+xml;utf8," + encodeURIComponent(
  `<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect width='300' height='180' fill='#171236'/><path d='M55 115h190l-16-38c-4-9-13-15-23-15H100c-10 0-19 6-24 15l-21 38z' fill='#7c3aed'/><circle cx='95' cy='120' r='18' fill='#090718' stroke='#a78bfa' stroke-width='3'/><circle cx='205' cy='120' r='18' fill='#090718' stroke='#a78bfa' stroke-width='3'/></svg>`
);

function updateGaugePreview(val) {
  const lbl = document.getElementById("sliderPctLabel");
  const gVal = document.getElementById("gaugeVal");
  const circle = document.getElementById("gaugeCircle");
  if (lbl) lbl.innerText = val + "%";
  if (gVal) gVal.innerText = val + "%";
  if (circle) {
    const circumference = 264;
    circle.style.strokeDashoffset = circumference - (val / 50) * circumference * 0.8;
  }
}