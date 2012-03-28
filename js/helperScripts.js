/**
 * Created by Jomaras.
 * Date: 28.03.12.@09:52
 */
(function getMRTCPublications()
{
    var publicationLinks = document.querySelectorAll("a[href^='index.php?choice=publications']");
    var publications = [];

    for(var i = 0; i < publicationLinks.length; i++)
    {
        var parentNode = publicationLinks[i].parentNode;
        var publication =
        {
            internalAuthors : [],
            externalAuthors: []
        };

        var childNodes = parentNode.childNodes;

        for(var j = 0; j < childNodes.length; j++)
        {
            var currentNode = childNodes[j];

            if(currentNode.localName == "a")
            {
                if(currentNode.href.indexOf("index.php?choice=publications") >= 0)
                {
                    publication.title = currentNode.textContent;
                    publication.link = currentNode.href;
                }
                else if(currentNode.href.indexOf("index.php?choice=staff") >= 0)
                {
                    publication.internalAuthors.push({name: currentNode.textContent, link: currentNode.href});
                }
            }
            else
            {
                var text = currentNode.textContent.replace(/^(\s)*,(\s)*/g, "").replace("(external)", "");

                var yearMatchArray = text.match(/20[0-1][0-9]/);

                if(yearMatchArray != null)
                {
                    publication.year = yearMatchArray[0];
                }
                else
                {
                    text = text.replace(/(^(\s+))|((\s+)$)/g, "");

                    if(text != "")
                    {
                        publication.externalAuthors.push({name: text});
                    }
                }
            }
        }

        publications.push(publication);
    }

    prompt("", JSON.stringify(publications));
})();

function replace()
{
    a.forEach(function(item)
    {
        item.externalAuthors.forEach(function(author){ author.name = author.name.replace("ö", "o").replace("–","-").replace("—","-").replace("å","a").replace("è","e").replace("é","e").replace("’","'").replace("“",'"').replace("”",'"').replace("™","").replace("ä","a");})
        item.internalAuthors.forEach(function(author){ author.name = author.name.replace("ö", "o").replace("–","-").replace("—","-").replace("å","a").replace("è","e").replace("é","e").replace("’","'").replace("“",'"').replace("”",'"').replace("™","").replace("ä","a");})
        item.title = item.title.replace("ö", "o").replace("–","-").replace("—","-").replace("å","a").replace("è","e").replace("é","e").replace("’","'").replace("“",'"').replace("”",'"').replace("™","").replace("ä","a");
    });

    prompt("",JSON.stringify(a));
}

