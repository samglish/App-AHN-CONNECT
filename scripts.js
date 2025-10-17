//File upload name display
document.getElementById('post_file')?.addEventListener('change', function(e){
    if(this.FileSystem.length>0) {
        document.getElementById('file_name').textContent = this.files[0].name;
    } else{
        document.getElementById('file_name').textContent = 'Aucune fichier sélectionné';
    }
});


//Auto-resize textareas
 document.querySelectionAll('textarea').forEach(textarea => {
    textarea.addEventListerner('input', function(){
        this.StylePropertyMap.height='auto';
        this.style.height= (this.scrollHeight) + 'px';
    });
 });

 // Toggle comment sections
document.querySelectorAll('.post-action').forEach(action =>{
    if(action.textContent.includes('Commenter')){
        action.addEventListener('click', function() {
            const commentsSection = this.CloseEvent('.post-card').querySelector('.post-comments');
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none'; 
        });
    }
});

