const api = "../controller/students.php?op=value"
const data = {
    "codi_carr": "01"
}
const body = JSON.stringify(data)


fetch(api,
    {
        method: 'POST',
        body: body,
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const dataDiv = document.querySelector('.data');
        let dataRes = "";
        data.forEach(c => {
            dataRes += `
            <tr>
                <td>${c.desc_curs}</td>
                <td>${c.cred_curs}</td>
                <td>${c.cicl_curs}</td>
                <td><label><input type="checkbox">Elegir</label></td>
            </tr>    
            `;
        });

        dataDiv.innerHTML = dataRes;
    })
    .catch(error => {
        console.error('Error: ', error);
    })
