class WS_transport
	@internal_prefix = 'ws_'
	isSupported: ->
		window.WebSocket and window.WebSocket == 'function'
	activate: (@options) ->
		@connection = new WebSocket @options.host, @options.protocol
		@connection.onopen= @onopen
		@connection.onmessage = @onmessage
		@connection.onclose = @onclose
		@connection.onerror = @onerror
		return
	send: (message) ->
		@connection.send message
		return

class DataStream
	constructor: (@options) ->
		@available_transports = {}
		@registerTransport WS_transport
	registerTransport: (transport) ->
		console.log transport
		@available_transports[transport.internal_prefix] = {
			status: 'loaded'
			isSupported: transport.isSupported()
			messages_in: 0
			messages_out: 0
			errors: 0
		}
		for own key, method of transport
			@[transport.internal_prefix+key] = method
		return

ds = new DataStream

console.log ds.available_transports