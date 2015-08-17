#### Class Mixins Extender
class Module
  @moduleKeywords = ['extended', 'included']
  @extend: (obj) ->
    @[key] = value for key, value of obj when key not in @moduleKeywords
    obj.extended?.apply(@)
    @

  @include: (obj) ->
    @::[key] = value for key, value of obj when key not in @moduleKeywords
    obj.included?.apply(@)
    @

#### Mixins
Arrays =
  object2array: (args) ->
    item for item in args if args and args.length > 0

Strings =
  pad: (string = '', width = 4, fill = ' ') ->
    if string.length < width then string + new Array(width - string.length + 1).join(fill) else string
#### End Mixins

#### Simple Logger
class Logger extends Module
  @include Arrays
  @include Strings
  format: (level, _args) ->
    args = @object2array _args
    args.unshift @pad '[' + level + '] ' + (new Date()).getTime() / 1000, 14
    return args
  log: ->
    args = @format 'debug', arguments
    Function::apply.call window.console.log, window.console, args
  error: ->
    args = @format 'error', arguments
    Function::apply.call window.console.error, window.console, args

#### Simple FIFO Queue Interface
class Queue
  constructor: ->
    @queue = []
  hasNext: ->
    @queue.length > 0
  enqueue: (item) ->
    @queue.unshift item if item?
  next: ->
    @queue.pop()


#### Main Trigger Class
class Trigger extends Logger
  constructor: () ->
    @events = {}
  _bind: (name, callback, once) ->
    @events[name] ?= new Queue()
    @events[name].enqueue {
      callback
      once
    } if typeof callback is 'function'
  bind: (name, callback) ->
    @_bind name, callback, false
  bindonce: (name, callback) ->
    @_bind name, callback, true
  unbind: (name) ->
    delete @events[name] if name in Object.keys @events
  execute: ->
    (@error "[trigger] Wrong trigger.execute call"; return false) if not arguments.length
    @trigger.apply(@, arguments)
  trigger: (name) ->
    (@error "[trigger] Name must be defined"; return false) if not name
    (@error "[trigger] Has no listeners for event: #{name}"; return false) if name not in Object.keys @events

    @log "[trigger] Executing event: #{name}"
    args = @object2array arguments
    queue = @events[name]
    _queue = new Queue()

    while queue.hasNext()
      task = queue.next()
      task.callback.apply window, args
      _queue.enqueue task if not task.once
    @events[name] = _queue
