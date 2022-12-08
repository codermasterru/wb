document.querySelector('.vg-element.vg-box-shadow.sitemap-button').onclick = (e) => {

    e.preventDefault();


    Ajax({type: 'POST'})
        .then((res) => {
            console.log('успех - ' + res)
        })
        .catch((res) => {
            console.log('неудача - ' + res)
        });

}

