<script>
function openQuickView(id){
  fetch('/SIRA_Cafe/api/product_quick.php?id='+id)
    .then(r=>r.text())
    .then(html=>{
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html;
      document.body.appendChild(wrapper);
    });
}
function closeModal(el){ el.closest('.modal')?.remove(); }
</script>
