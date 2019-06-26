module.exports = {
	created(){
		this.buscarListagem(1)
  	},
  	data() {
  		return {
  			clientes: [],
  			cliente: {},
  			pagina: 0,
  			totalPaginas: 1,
  			totalPorPagina: 20,
  			total: 0,
            termoPesquisa: '',
            listagemFiltrada: false,
            mostrarForm: false
  		}
  	},
    methods: {
        cadastrarNovoCliente() {
            this.mostrarForm = true
            this.cliente = {}
        },
        limparFiltro() {
            this.termoPesquisa = '';
            this.buscarListagem(1)
        },
        buscarListagem(pagina) {
        	if (pagina >= 1 && pagina <= this.totalPaginas) {
	        	listagem = sendRequest('api/Cliente/buscarListagem', {pagina, totalPorPagina: this.totalPorPagina, termoPesquisa: this.termoPesquisa})
                this.listagemFiltrada = this.termoPesquisa != ''
	        	this.clientes = listagem.dados
	        	this.total = listagem.total
	        	this.pagina = pagina
	        	this.totalPaginas = Math.ceil(this.total / this.totalPorPagina)
	        	if (this.totalPaginas && this.pagina > this.totalPaginas) {
	        		this.buscarListagem(this.totalPaginas)
	        	}
	        }
        },
        salvarDados(event) {
        	if (sendFormRequest(event.target.form)) {
        		this.cliente = {}
                this.mostrarForm = false
        		this.limparFiltro()
        	}
        },
        buscarDadosEdicao(idcliente) {
            this.cliente = sendRequest('api/Cliente/buscarDadosEdicao', {idcliente})
            this.mostrarForm = true
        },
        removerDados(idcliente) {
        	if (sendRequest('api/Cliente/removerDados', {idcliente}, 'POST')) {
        		this.buscarListagem(this.pagina)
        	}
        }
    }
}