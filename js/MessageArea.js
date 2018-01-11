
function MessageArea(container)
{
    this._container = container;
}

MessageArea.MessageType =
    {
        Success: 0,
        Information: 1,
        Error: 2,
        Alert: 3
    };

MessageArea.prototype._getCssClassByMessageType = function(messageType)
{
    switch (messageType)
    {
        case MessageArea.MessageType.Success:
            return "message_success";
        case MessageArea.MessageType.Information:
            return "message_information";
        case MessageArea.MessageType.Error:
            return "message_error";
        case MessageArea.MessageType.Alert:
            return "message_alert";
    }
}

MessageArea.prototype.Clear = function()
{
    while (this._container.childNodes.length > 0)
        Element.remove(this._container.childNodes[0]);
}

MessageArea.prototype.AddMessage = function(htmlText, messageType)
{
    var messageElement = $(document.createElement("p"));
    messageElement.addClassName(this._getCssClassByMessageType(messageType));
    messageElement.innerHTML = htmlText;
    this._container.appendChild(messageElement);
}

MessageArea.prototype.AddSuccess = function(htmlText)
{
    this.AddMessage(htmlText, MessageArea.MessageType.Success);
}

MessageArea.prototype.AddInformation = function(htmlText)
{
    this.AddMessage(htmlText, MessageArea.MessageType.Information);
}

MessageArea.prototype.AddError = function(htmlText)
{
    this.AddMessage(htmlText, MessageArea.MessageType.Error);
}

MessageArea.prototype.AddAlert = function(htmlText)
{
    this.AddMessage(htmlText, MessageArea.MessageType.Alert);
}