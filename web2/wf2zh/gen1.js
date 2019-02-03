function $(sel) {
    return document.querySelector(sel);
}

function xyKoord(td) {
    var x =  td.cellIndex;
    var tr = td.parentNode;
    var y =  tr.sectionRowIndex;
    return {
      x: x,
      y: y
    };
  }

let x = [];
function general(n, m) {
    const x = [];
    for (let i = 0; i<n; i++) {
        const sor = [];
        for (let j = 0; j<m; j++) {
            sor.push(0);
        }   
        x.push(sor); 
    }
    return x;
}

$('#general').addEventListener('click', onGeneral);
function onGeneral(e) {
    const n = parseInt( $('#magassag').value );
    const m = parseInt( $('#szelesseg').value );
    x = general(n, m);
    $('#tabla').innerHTML = genTable(x);
    $('#alakzat').value = JSON.stringify(x); 
    onShow();
}

$('table').addEventListener('click', onTableClick);
function onTableClick(e) {
    if (e.target.matches('td')) {
        const td = e.target;
        const {x:j, y:i} = xyKoord(td);
        x[i][j]++;
        $('#alakzat').value = JSON.stringify(x); 
        onShow();
        $('#tabla').innerHTML = genTable(x);
    }
}

function genTable(x) {
    return `
        ${x.map(sor => `
            <tr>
                ${sor.map(e => `
                    <td>${e}</td>
                `).join('')}
            </tr>
        `).join('')}
    `;
}

function genTable2(x) {
    return `
        ${x.map(sor => `
            <tr>
                ${sor.map(e => `
                    <td class="${e > 0 ? 'black' : ''}"></td>
                `).join('')}
            </tr>
        `).join('')}
    `;
}

$('#megjelenit').addEventListener('click', onShow)
function onShow(e) {
    const x = JSON.parse($('#alakzat').value);
    const balrol = x.map(sor => sor.reduce((max, e) => e > max ? e : max, -Infinity));
    const alulrol = x[0].map((e, i) => x.reduce((max, e) => e[i] > max ? e[i] : max, -Infinity))
    $('#felul').innerHTML = genTable2(x);
    $('#bal').innerHTML = genVetulet(balrol);
    $('#alul').innerHTML = genVetulet(alulrol);
}

const range = n => Array.from({length: n});

function genVetulet(x) {
    return `
        ${x.map((o, i) => `
            <div>
                ${range(o).map(e => `<div></div>`).join('')}
                
            </div>
        `).join('')}
    `;
}