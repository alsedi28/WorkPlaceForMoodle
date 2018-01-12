
function MessageArea(container)
{
    this._container = container;
}

MessageArea.MessageType =
    {
        Success: 0,
        Information: 1,
        Error: 2,
        Loading: 3
    };

MessageArea.prototype._getImageNameByMessageType = function(messageType)
{
    switch (messageType)
    {
        case MessageArea.MessageType.Success:
            return "success.png";
        case MessageArea.MessageType.Information:
            return "information.png";
        case MessageArea.MessageType.Error:
            return "attention.png";
        case MessageArea.MessageType.Loading:
            return "loading.gif";
    }
}

MessageArea.prototype._isEmpty = function () {
    return this._container.children.length == 0;
}

MessageArea.prototype.Clear = function()
{
    if(!this._isEmpty()){
        this._container.innerHTML = "";
        this._container.classList.remove('message_container');
    }
}

MessageArea.prototype._addMessage = function(text, messageType)
{
    if(this._isEmpty()){
        this._container.classList.add('message_container');

        var imgElement = document.createElement("img");
        imgElement.src = 'img/' + this._getImageNameByMessageType(messageType);
        imgElement.className = 'message_icon';
        this._container.appendChild(imgElement);

        var messageElement = document.createElement("p");
        messageElement.className = 'message_text';
        messageElement.innerHTML = text;
        this._container.appendChild(messageElement);
    }
    else{
        var imgElement = this._container.querySelector('img.message_icon');
        imgElement.src = 'img/' + this._getImageNameByMessageType(messageType);

        var messageElement = this._container.querySelector('p.message_text');
        messageElement.innerHTML = text;
    }
}

MessageArea.prototype.AddSuccess = function(text)
{
    this._addMessage(text, MessageArea.MessageType.Success);
}

MessageArea.prototype.AddInformation = function(text)
{
    this._addMessage(text, MessageArea.MessageType.Information);
}

MessageArea.prototype.AddError = function(text)
{
    this._addMessage(text, MessageArea.MessageType.Error);
}

MessageArea.prototype.AddLoading = function(text)
{
    this._addMessage(text, MessageArea.MessageType.Loading);
}