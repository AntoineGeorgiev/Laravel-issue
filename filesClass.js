class FileUpload {
    constructor(){
        this.filesModal   = document.querySelector('#add-file');
        this.addFileBtn   = document.querySelector('#add-new-file');
        this.modalHeader  = document.querySelector("#add-file > section > div > div.modal-header > h5");
        this.btnClose     = document.querySelectorAll('[data-role="close"]');
        this.addBtn       = document.querySelector("#btnOKFile");
        this.fileName     = document.querySelector('#fileNameM');
        this.lblFile      = document.querySelector('#lblFile');
        this.loadFileBtn  = document.querySelector('#loadFileXml');
        this.docType      = document.querySelector("#document_type");
        this.explanations = document.querySelector("#explanations");

        this.filesTable   = document.querySelector("#files");
        this.availRows    = this.filesTable.querySelectorAll('tr').length;
    }
    init(){
        sessionStorage.clear();

        //press add file button
        this.addFileBtn.addEventListener('click', e => {
            e.preventDefault();
            this.filesModal.style.display = 'block';
            this.modalHeader.innerText    = 'Добавяне на файл';
            //Initialize
            this.lblFile.innerText  = '  Няма въведен файл';
            this.fileName.value     = '';
            this.explanations.value = '';
            this.docType.value      = 0;

            //select a file
            this.fileName.addEventListener('change', e => {
                //console.log(e.target.files[0].name);
                lblFile.innerText = e.target.files[0].name;
            });
        });

        //button Exit on the modal
        this.btnClose.forEach(btn => {
            btn.addEventListener('click', e => {
                //console.log('btn');
                e.preventDefault();
                this.filesModal.style.display = 'none';
            });
        });

        //button Add files on the modal
        this.addBtn.addEventListener('click', e => {
            e.preventDefault();

            this.filesModal.style.display = 'none';

            this.addFileRow(this.availRows);

            //since browser resets all input files, they must be set again
            const inputFiles = this.filesTable.querySelector("[type='file']");
            
                // name: "300px-Calm_down.jpg"
                // size: 24600
                // type: "image/jpeg"
                //file.files = JSON.parse(sessionStorage.getItem(file.name));
            inputFiles[0].file[0]['name'] = "300px-Calm_down.jpg";
        });
        
    }

    extractFilename(path) {
        if (path.substr(0, 12) == "C:\\fakepath\\")
          return path.substr(12); // modern browser
        var x;
        x = path.lastIndexOf('/');
        if (x >= 0) // Unix-based path
            return path.substr(x+1);
        x = path.lastIndexOf('\\');
        if (x >= 0) // Windows-based path
            return path.substr(x+1);
        return path; // just the file name
    }

    addFileRow(row) {
        const fileList = this.fileName.files;
        let docuType = '';
        for (let index = 0; index < this.docType.options.length; index++) {
            if (this.docType.options[index].selected) {
                docuType = this.docType.options[index].label;
            }
            
        }
        //console.log(this.docuType);
        let cell = [];
        cell[0] =  `
            <input hidden id="document_type${row}" name="document_type${row}" value="${this.docType.value}" />
            ${docuType}
        `;
        cell[1] =  `
            <input hidden id="explanations${row}" name="explanations${row}" value="${this.explanations.value}" />
            ${this.explanations.value}
        `;
        cell[2] =  `
            <input type="file" id="fileName${row}" name="fileName${row}" />
            ${this.lblFile.innerText}
        `;
        cell[3] =  `
            <input hidden type="text" id="info${row}" name="info${row}" value="new" />
            <input hidden type="text" class="deleted" id="deleted${row}" name="deleted${row}" value="false" />
            <button type="button" class="btnDelFile btn-danger btn-sm" ><i class="fas fa-trash-alt"></i></button>
        `;

        const tableRow = this.filesTable.insertRow();
        for (let index = 0; index < cell.length; index++) {
            const element = tableRow.insertCell(index);
            element.innerHTML = cell[index];
        }
        
        //add the files to the table
        const ime = `#fileName${row}`;
        //console.log(ime, typeof ime);
        const currFile = this.filesTable.querySelector(ime);
        currFile.files = fileList;
        //document.querySelector('#testFile' + row).files = fileList;
        sessionStorage.setItem(`fileName${row}`, JSON.stringify(fileList[0]));
        //console.log(document.querySelector('#fileName' + row).files);

        this.initDeleteBtns();

        //a new row has been added
        this.availRows++;
    }

    initDeleteBtns = () => {
        //btn Delete in the table row
        const btnDelete   = document.querySelectorAll('.btnDelFile');
    
        btnDelete.forEach(btn => {
            //console.log(btn.getAttribute('listener'));
            if (btn.getAttribute('listener') === null) {            
                btn.addEventListener('click', e => {
                    e.preventDefault();                
                    
                    const currentRow = e.target.closest('tr'); //current table row
                    
                    const button = currentRow.querySelector('.btnDelFile');
                    button.setAttribute('listener', true); //shows that there is a listener attached
                    
                    //console.log(currentRow.getAttribute('style'));
                    const delClass = currentRow.querySelector('.deleted');
                    
                    if (currentRow.getAttribute('style')) {
                    currentRow.removeAttribute('style');
                    delClass.setAttribute('value', 'false');
                    } else {
                    currentRow.setAttribute('style', 'text-decoration:line-through;text-decoration-color:red');
                    delClass.setAttribute('value', 'true');
                    //console.log(delClass);
        
                    }
                    
                    //console.log(delClass.value);
                });
            }
        });
    }
    
}